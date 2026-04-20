<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $search = trim((string) request('search', ''));
        $statusFilter = request('status', 'all');

        $query = Customer::selectRaw('customers.*, 
                COALESCE(SUM(CASE WHEN ps.name = "paid" THEN rf.quantity * rf.unit_price ELSE 0 END), 0) as total_paid,
                COALESCE(SUM(CASE WHEN ps.name = "unpaid" THEN rf.quantity * rf.unit_price ELSE 0 END), 0) as total_unpaid,
                COALESCE(SUM(CASE WHEN ps.name = "partial" THEN rf.quantity * rf.unit_price ELSE 0 END), 0) as total_partial')
            ->leftJoin('refills as rf', 'customers.id', '=', 'rf.customer_id')
            ->leftJoin('payment_statuses as ps', 'rf.payment_status_id', '=', 'ps.id');

        // Filter by payment status
        if ($statusFilter === 'unpaid') {
            $query->havingRaw('total_unpaid > 0');
        } elseif ($statusFilter === 'partial') {
            $query->havingRaw('total_unpaid = 0 AND total_partial > 0');
        } elseif ($statusFilter === 'paid') {
            $query->havingRaw('total_unpaid = 0 AND total_partial = 0');
        }

        $customers = $query
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($nested) use ($search) {
                    $nested->where('customers.name', 'like', '%' . $search . '%')
                        ->orWhere('customers.phone', 'like', '%' . $search . '%')
                        ->orWhere('customers.id', 'like', '%' . $search . '%');
                });
            })
            ->groupBy('customers.id')
            ->orderBy('customers.name')
            ->paginate(10)
            ->withQueryString();

        // Get summary statistics
        $allCustomers = Customer::selectRaw('customers.*, 
                COALESCE(SUM(CASE WHEN ps.name = "paid" THEN rf.quantity * rf.unit_price ELSE 0 END), 0) as total_paid,
                COALESCE(SUM(CASE WHEN ps.name = "unpaid" THEN rf.quantity * rf.unit_price ELSE 0 END), 0) as total_unpaid,
                COALESCE(SUM(CASE WHEN ps.name = "partial" THEN rf.quantity * rf.unit_price ELSE 0 END), 0) as total_partial')
            ->leftJoin('refills as rf', 'customers.id', '=', 'rf.customer_id')
            ->leftJoin('payment_statuses as ps', 'rf.payment_status_id', '=', 'ps.id')
            ->groupBy('customers.id')
            ->get();

        $stats = [
            'total_customers' => $allCustomers->count(),
            'unpaid_customers' => $allCustomers->filter(fn($c) => $c->total_unpaid > 0)->count(),
            'partial_customers' => $allCustomers->filter(fn($c) => $c->total_unpaid == 0 && $c->total_partial > 0)->count(),
            'paid_customers' => $allCustomers->filter(fn($c) => $c->total_unpaid == 0 && $c->total_partial == 0)->count(),
            'total_outstanding' => $allCustomers->sum(fn($c) => ($c->total_unpaid ?? 0) + ($c->total_partial ?? 0)),
        ];

        return view('aquaheart.customers.index', compact('customers', 'stats', 'statusFilter'));
    }

    public function search(Request $request)
    {
        $term = trim((string) $request->query('term', ''));

        if ($term === '') {
            return response()->json(['data' => []]);
        }

        $customers = Customer::query()
            ->select(['id', 'name', 'phone'])
            ->where(function ($query) use ($term) {
                $query->where('name', 'like', '%' . $term . '%')
                    ->orWhere('phone', 'like', '%' . $term . '%');
            })
            ->orderBy('name')
            ->limit(10)
            ->get();

        return response()->json([
            'data' => $customers,
        ]);
    }

    public function create()
    {
        return view('aquaheart.customers.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'street' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
        ]);

        $data['loyalty_points'] = 0;
        
        Customer::create($data);
        return redirect()->route('aquaheart.customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        $customer->load('refills.product', 'refills.paymentStatus');
        return view('aquaheart.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('aquaheart.customers.form', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name' => 'required|string|min:2|max:255',
            'phone' => 'nullable|string|max:20',
            'street' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
        ]);
        
        $customer->update($data);
        return redirect()->route('aquaheart.customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'message' => 'Customer deleted successfully.',
            ]);
        }

        return redirect()->route('aquaheart.customers.index')->with('success', 'Customer deleted successfully.');
    }
}

