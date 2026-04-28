<?php

namespace App\Http\Controllers;

use App\Models\Refill;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class RefillController extends Controller
{
    public function index()
    {
        $statusFilter = trim((string) request('status', ''));
        $query = Refill::with(['customer', 'product', 'user', 'paymentStatus'])->select('refills.*');
        
        if (!auth()->user()->is_admin) {
            $query->where('user_id', auth()->id());
        }

        $this->applyPaymentStatusFilter($query, $statusFilter);
        
        $refills = $query->latest()->paginate(10);
        $paymentStatusCounts = [
            'all' => $this->countPaymentStatusRecords(null),
            'paid' => $this->countPaymentStatusRecords('paid'),
            'unpaid' => $this->countPaymentStatusRecords('unpaid'),
            'partial' => $this->countPaymentStatusRecords('partial'),
        ];

        return view('aquaheart.refills.index', compact('refills', 'statusFilter', 'paymentStatusCounts'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('aquaheart.refills.form', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'payment_status' => 'required|in:paid,unpaid,partial',
            'service_type' => 'required|in:walk_in,delivery,pickup',
            'notes' => 'nullable|string|max:1000',
            'refill_date' => 'required|date',
            'paid_amount' => 'nullable|numeric|min:0',
            'partial_amount' => 'nullable|numeric|min:0',
        ]);
        
        // Get or create customer
        if (!empty($data['customer_id'])) {
            $customerId = $data['customer_id'];
        } else {
            // Search for existing customer (case-insensitive)
            $customer = Customer::whereRaw('LOWER(name) = ?', [strtolower($data['customer_name'])])->first();
            
            if ($customer) {
                $customerId = $customer->id;
            } else {
                // Create new customer
                $customer = Customer::create(['name' => $data['customer_name']]);
                $customerId = $customer->id;
            }
        }
        
        $receiptNumber = 'AQ-' . now()->format('Ymd') . '-' . str_pad((string) (Refill::count() + 1), 4, '0', STR_PAD_LEFT);
        $product = Product::findOrFail($data['product_id']);

        if ($product->stock_quantity < $data['quantity']) {
            return back()
                ->withErrors(['quantity' => 'Insufficient stock for the selected product.'])
                ->withInput();
        }

        Refill::create($this->buildRefillPayload($data, $customerId, $receiptNumber));

        $product->decrement('stock_quantity', $data['quantity']);
        Customer::whereKey($customerId)->increment('loyalty_points', $data['quantity']);
        
        return redirect()->route('aquaheart.refills.index')->with('success', 'Refill recorded successfully.');
    }

    public function show(Refill $refill)
    {
        if (!auth()->user()->is_admin && $refill->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this transaction.');
        }

        $refill->load(['customer', 'product', 'user', 'paymentStatus']);
        return view('aquaheart.refills.show', compact('refill'));
    }

    public function edit(Refill $refill)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Cashiers are not permitted to modify recorded transactions.');
        }

        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('aquaheart.refills.form', compact('refill', 'customers', 'products'));
    }

    public function update(Request $request, Refill $refill)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Cashiers are not permitted to modify recorded transactions.');
        }

        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'payment_status' => 'required|in:paid,unpaid,partial',
            'service_type' => 'required|in:walk_in,delivery,pickup',
            'notes' => 'nullable|string|max:1000',
            'refill_date' => 'required|date',
            'paid_amount' => 'nullable|numeric|min:0',
            'partial_amount' => 'nullable|numeric|min:0',
        ]);
        
        // Get or create customer
        if (!empty($data['customer_id'])) {
            $customerId = $data['customer_id'];
        } else {
            // Search for existing customer (case-insensitive)
            $customer = Customer::whereRaw('LOWER(name) = ?', [strtolower($data['customer_name'])])->first();
            
            if ($customer) {
                $customerId = $customer->id;
            } else {
                // Create new customer
                $customer = Customer::create(['name' => $data['customer_name']]);
                $customerId = $customer->id;
            }
        }
        
        $previousCustomerId = $refill->customer_id;
        $previousProductId = $refill->product_id;
        $previousQuantity = $refill->quantity ?? 0;
        $requestedProduct = Product::findOrFail($data['product_id']);
        $availableStock = (string) $previousProductId === (string) $data['product_id']
            ? $requestedProduct->stock_quantity + $previousQuantity
            : $requestedProduct->stock_quantity;

        if ($availableStock < $data['quantity']) {
            return back()
                ->withErrors(['quantity' => 'Insufficient stock for the selected product.'])
                ->withInput();
        }

        $refill->update($this->buildRefillPayload($data, $customerId));

        if ((string) $previousProductId === (string) $data['product_id']) {
            $difference = $data['quantity'] - $previousQuantity;
            if ($difference > 0) {
                Product::whereKey($data['product_id'])->decrement('stock_quantity', $difference);
            } elseif ($difference < 0) {
                Product::whereKey($data['product_id'])->increment('stock_quantity', abs($difference));
            }
        } else {
            Product::whereKey($previousProductId)->increment('stock_quantity', $previousQuantity);
            Product::whereKey($data['product_id'])->decrement('stock_quantity', $data['quantity']);
        }

        if ($previousCustomerId === $customerId) {
            $loyaltyDifference = $data['quantity'] - $previousQuantity;
            if ($loyaltyDifference > 0) {
                Customer::whereKey($customerId)->increment('loyalty_points', $loyaltyDifference);
            } elseif ($loyaltyDifference < 0) {
                Customer::whereKey($customerId)->decrement('loyalty_points', abs($loyaltyDifference));
            }
        } else {
            Customer::whereKey($previousCustomerId)->decrement('loyalty_points', $previousQuantity);
            Customer::whereKey($customerId)->increment('loyalty_points', $data['quantity']);
        }
        
        return redirect()->route('aquaheart.refills.index')->with('success', 'Refill updated successfully.');
    }

    private function buildRefillPayload(array $data, string $customerId, ?string $receiptNumber = null): array
    {
        $payload = [
            'customer_id' => $customerId,
            'product_id' => $data['product_id'],
            'quantity' => $data['quantity'],
            'unit_price' => $data['unit_price'],
            'notes' => $data['notes'] ?? null,
        ];

        if (Schema::hasColumn('refills', 'user_id')) {
            $payload['user_id'] = auth()->id();
        }

        if ($receiptNumber !== null && Schema::hasColumn('refills', 'receipt_number')) {
            $payload['receipt_number'] = $receiptNumber;
        }

        if (Schema::hasColumn('refills', 'amount')) {
            $payload['amount'] = $data['quantity'] * $data['unit_price'];
        }

        if (Schema::hasColumn('refills', 'payment_status')) {
            $payload['payment_status'] = $data['payment_status'];
        }

        if (Schema::hasColumn('refills', 'service_type')) {
            $payload['service_type'] = $data['service_type'];
        }

        if (Schema::hasColumn('refills', 'payment_status_id')) {
            $payload['payment_status_id'] = $this->resolveLookupId('payment_statuses', $data['payment_status']);
        }

        if (Schema::hasColumn('refills', 'service_type_id')) {
            $payload['service_type_id'] = $this->resolveLookupId('service_types', $data['service_type']);
        }

        if (Schema::hasColumn('refills', 'refill_date')) {
            $payload['refill_date'] = $data['refill_date'];
        }

        if (Schema::hasColumn('refills', 'paid_amount') || Schema::hasColumn('refills', 'partial_amount')) {
            $total = $data['quantity'] * $data['unit_price'];
            $isPartial = $data['payment_status'] === 'partial';
            
            if ($isPartial) {
                // If partial but no amounts provided, assume 50% paid as fallback or keep as is if one is provided
                $paid = $data['paid_amount'] ?? ($data['partial_amount'] !== null ? $total - $data['partial_amount'] : $total / 2);
                $remaining = $data['partial_amount'] ?? ($total - $paid);
                
                $payload['paid_amount'] = $paid;
                $payload['partial_amount'] = $remaining;
            } else if ($data['payment_status'] === 'paid') {
                $payload['paid_amount'] = $total;
                $payload['partial_amount'] = 0;
            } else {
                $payload['paid_amount'] = 0;
                $payload['partial_amount'] = $total;
            }
        }

        return $payload;
    }

    private function resolveLookupId(string $table, string $value): ?string
    {
        if (!Schema::hasTable($table)) {
            return null;
        }

        $needle = strtolower(trim($value));
        
        // Add fallback aliases for payment statuses
        if ($table === 'payment_statuses') {
            if ($needle === 'partial') $candidates = ['partial', 'pending'];
            else if ($needle === 'pending') $candidates = ['pending', 'partial'];
            else $candidates = [$needle];
        } else {
            $candidates = [$needle];
        }

        foreach ($candidates as $term) {
            // Try code column first
            if (Schema::hasColumn($table, 'code')) {
                $match = DB::table($table)->whereRaw('LOWER(code) = ?', [$term])->value('id');
                if ($match !== null) return (string) $match;
            }

            // Try name column
            if (Schema::hasColumn($table, 'name')) {
                $match = DB::table($table)->whereRaw('LOWER(name) = ?', [$term])->value('id');
                if ($match !== null) return (string) $match;
            }
        }

        return null;
    }

    public function destroy(Refill $refill)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Cashiers are not permitted to delete transactions.');
        }

        Product::whereKey($refill->product_id)->increment('stock_quantity', $refill->quantity ?? 0);
        Customer::whereKey($refill->customer_id)->decrement('loyalty_points', $refill->quantity ?? 0);
        $refill->delete();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'message' => 'Refill deleted successfully.',
            ]);
        }

        return redirect()->route('aquaheart.refills.index')->with('success', 'Refill deleted successfully.');
    }

    public function updatePaymentStatus(Request $request, Refill $refill)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Cashiers are not permitted to modify transaction statuses.');
        }

        $data = $request->validate([
            'payment_status' => 'required|in:paid,unpaid,partial',
        ]);

        $this->setPaymentStatus($refill, $data['payment_status']);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Payment status updated successfully.',
            ]);
        }

        return back()->with('success', 'Payment status updated successfully.');
    }

    private function applyPaymentStatusFilter($query, ?string $status): void
    {
        if ($status === '' || $status === null || $status === 'all') {
            return;
        }

        if (Schema::hasColumn('refills', 'paid_amount') && Schema::hasColumn('refills', 'partial_amount')) {
            $status = strtolower($status);

            $query->where(function ($innerQuery) use ($status) {
                $this->applyDerivedPaymentStatusFilter($innerQuery, $status);

                $innerQuery->orWhere(function ($fallbackQuery) use ($status) {
                    $fallbackQuery->whereNull('refills.paid_amount')
                        ->whereNull('refills.partial_amount');

                    $this->applyStoredPaymentStatusFilter($fallbackQuery, $status);
                });
            });

            return;
        }

        $this->applyStoredPaymentStatusFilter($query, $status);
    }

    private function applyStoredPaymentStatusFilter($query, string $status): void
    {
        $status = strtolower($status);

        if (Schema::hasColumn('refills', 'payment_status')) {
            $query->whereRaw('LOWER(refills.payment_status) = ?', [$status]);
            return;
        }

        if (!Schema::hasColumn('refills', 'payment_status_id')) {
            return;
        }

        if (Schema::hasColumn('payment_statuses', 'code')) {
            $query->whereHas('paymentStatus', function ($paymentStatusQuery) use ($status) {
                $paymentStatusQuery->whereRaw('LOWER(code) = ?', [$status]);
            });
            return;
        }

        if (Schema::hasColumn('payment_statuses', 'name')) {
            $query->whereHas('paymentStatus', function ($paymentStatusQuery) use ($status) {
                $paymentStatusQuery->whereRaw('LOWER(name) = ?', [strtolower(str_replace('_', ' ', $status))]);
            });
        }
    }

    private function applyDerivedPaymentStatusFilter($query, string $status): void
    {
        $totalExpression = '(COALESCE(refills.quantity, 0) * COALESCE(refills.unit_price, 0))';

        if ($status === 'partial') {
            $query->whereRaw('COALESCE(refills.paid_amount, 0) > 0')
                ->whereRaw('COALESCE(refills.partial_amount, 0) > 0');
            return;
        }

        if ($status === 'paid') {
            $query->whereRaw('COALESCE(refills.partial_amount, 0) <= 0')
                ->whereRaw('COALESCE(refills.paid_amount, 0) >= ' . $totalExpression);
            return;
        }

        if ($status === 'unpaid') {
            $query->whereRaw('COALESCE(refills.paid_amount, 0) <= 0')
                ->whereRaw('COALESCE(refills.partial_amount, 0) > 0');
        }
    }

    private function countPaymentStatusRecords(?string $status): int
    {
        $query = Refill::query();

        if (!auth()->user()->is_admin) {
            $query->where('user_id', auth()->id());
        }

        if ($status === null) {
            return $query->count();
        }

        $this->applyPaymentStatusFilter($query, $status);

        return $query->count();
    }

    private function setPaymentStatus(Refill $refill, string $status): void
    {
        $total = ($refill->quantity ?? 0) * ($refill->unit_price ?? 0);

        if (Schema::hasColumn('refills', 'paid_amount')) {
            if ($status === 'paid') {
                $refill->paid_amount = $total;
                $refill->partial_amount = 0;
            } elseif ($status === 'unpaid') {
                $refill->paid_amount = 0;
                $refill->partial_amount = $total;
            } elseif ($status === 'partial' && ($refill->paid_amount == $total || $refill->paid_amount == 0)) {
                // If switching to partial from a full state, assume 50% split if not already partially paid
                $refill->paid_amount = $total / 2;
                $refill->partial_amount = $total / 2;
            }
        }

        if (Schema::hasColumn('refills', 'payment_status')) {
            $refill->payment_status = $status;
        }

        if (Schema::hasColumn('refills', 'payment_status_id')) {
            $refill->payment_status_id = $this->resolveLookupId('payment_statuses', $status);
        }

        $refill->save();
    }
}
