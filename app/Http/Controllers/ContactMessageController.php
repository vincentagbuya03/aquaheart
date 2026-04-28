<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        if (!$request->user()?->is_admin) {
            abort(403, 'Unauthorized access to contact messages.');
        }

        $query = ContactMessage::query()->latest('created_at');

        $search = trim((string) $request->query('q', ''));
        if ($search !== '') {
            $query->where(function ($inner) use ($search) {
                $inner->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('inquiry', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $status = trim((string) $request->query('status', 'all'));
        if ($status === 'unread') {
            $query->where('is_read', false);
        } elseif ($status === 'read') {
            $query->where('is_read', true);
        }

        $messages = $query->paginate(12)->withQueryString();

        $stats = [
            'total' => ContactMessage::count(),
            'unread' => ContactMessage::where('is_read', false)->count(),
            'today' => ContactMessage::whereDate('created_at', now()->toDateString())->count(),
        ];

        return view('aquaheart.messages.index', compact('messages', 'stats', 'search', 'status'));
    }

    public function markAsRead(Request $request, ContactMessage $contactMessage): RedirectResponse
    {
        if (!$request->user()?->is_admin) {
            abort(403, 'Unauthorized access to contact messages.');
        }

        if (!$contactMessage->is_read) {
            $contactMessage->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return back()->with('success', 'Message marked as read.');
    }
}
