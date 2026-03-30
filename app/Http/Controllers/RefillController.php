<?php

namespace App\Http\Controllers;

use App\Models\Refill;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class RefillController extends Controller
{
    public function index()
    {
        $refills = Refill::with(['customer', 'product'])->latest()->paginate(10);
        return view('aquaheart.refills.index', compact('refills'));
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
        $totalAmount = $data['quantity'] * $data['unit_price'];
        $product = Product::findOrFail($data['product_id']);

        if ($product->stock_quantity < $data['quantity']) {
            return back()
                ->withErrors(['quantity' => 'Insufficient stock for the selected product.'])
                ->withInput();
        }

        Refill::create([
            'customer_id' => $customerId,
            'product_id' => $data['product_id'],
            'receipt_number' => $receiptNumber,
            'quantity' => $data['quantity'],
            'unit_price' => $data['unit_price'],
            'amount' => $totalAmount,
            'payment_status' => $data['payment_status'],
            'service_type' => $data['service_type'],
            'notes' => $data['notes'] ?? null,
            'refill_date' => $data['refill_date'],
        ]);

        $product->decrement('stock_quantity', $data['quantity']);
        Customer::whereKey($customerId)->increment('loyalty_points', $data['quantity']);
        
        return redirect()->route('aquaheart.refills.index')->with('success', 'Refill recorded successfully.');
    }

    public function show(Refill $refill)
    {
        $refill->load(['customer', 'product']);
        return view('aquaheart.refills.show', compact('refill'));
    }

    public function edit(Refill $refill)
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('aquaheart.refills.form', compact('refill', 'customers', 'products'));
    }

    public function update(Request $request, Refill $refill)
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
        $totalAmount = $data['quantity'] * $data['unit_price'];
        $requestedProduct = Product::findOrFail($data['product_id']);
        $availableStock = $previousProductId === (int) $data['product_id']
            ? $requestedProduct->stock_quantity + $previousQuantity
            : $requestedProduct->stock_quantity;

        if ($availableStock < $data['quantity']) {
            return back()
                ->withErrors(['quantity' => 'Insufficient stock for the selected product.'])
                ->withInput();
        }

        $refill->update([
            'customer_id' => $customerId,
            'product_id' => $data['product_id'],
            'quantity' => $data['quantity'],
            'unit_price' => $data['unit_price'],
            'amount' => $totalAmount,
            'payment_status' => $data['payment_status'],
            'service_type' => $data['service_type'],
            'notes' => $data['notes'] ?? null,
            'refill_date' => $data['refill_date'],
        ]);

        if ($previousProductId === (int) $data['product_id']) {
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

    public function destroy(Refill $refill)
    {
        Product::whereKey($refill->product_id)->increment('stock_quantity', $refill->quantity ?? 0);
        Customer::whereKey($refill->customer_id)->decrement('loyalty_points', $refill->quantity ?? 0);
        $refill->delete();
        return redirect()->route('aquaheart.refills.index')->with('success', 'Refill deleted successfully.');
    }
}
