<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('aquaheart.users.index', compact('users'));
    }

    public function create()
    {
        return view('aquaheart.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,cashier',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->role === 'admin',
            'is_cashier' => $request->role === 'cashier',
        ]);

        return redirect()->route('aquaheart.users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        return view('aquaheart.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('aquaheart.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,cashier',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'is_admin' => $request->role === 'admin',
            'is_cashier' => $request->role === 'cashier',
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('aquaheart.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'message' => 'You cannot delete yourself.',
                ], 422);
            }

            return back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'message' => 'User deleted successfully.',
            ]);
        }

        return redirect()->route('aquaheart.users.index')->with('success', 'User deleted successfully.');
    }
}
