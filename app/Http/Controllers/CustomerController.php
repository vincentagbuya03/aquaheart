<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::withCount('refills')->orderBy('name')->paginate(10);
        return view('aquaheart.customers.index', compact('customers'));
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
            'address' => 'nullable|string|max:255',
        ]);

        $data['loyalty_points'] = 0;
        
        Customer::create($data);
        return redirect()->route('aquaheart.customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        $customer->load('refills.product');
        return view('aquaheart.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('aquaheart.customers.form', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);
        
        $customer->update($data);
        return redirect()->route('aquaheart.customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('aquaheart.customers.index')->with('success', 'Customer deleted successfully.');
    }
}

