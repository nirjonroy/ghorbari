<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(): View
    {
        $contacts = ContactMessage::query()
            ->with('user')
            ->latest()
            ->paginate(15);

        return view('Admin.contacts.index', compact('contacts'));
    }

    public function edit(ContactMessage $contact): View
    {
        $contact->load('user');

        return view('Admin.contacts.edit', compact('contact'));
    }

    public function update(Request $request, ContactMessage $contact): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message_type' => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string'],
            'source_page' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['new', 'read', 'replied', 'closed', 'spam'])],
            'admin_note' => ['nullable', 'string'],
            'read_at' => ['nullable', 'date'],
            'replied_at' => ['nullable', 'date'],
        ]);

        if ($data['status'] === 'read' && empty($data['read_at'])) {
            $data['read_at'] = now();
        }

        if ($data['status'] === 'replied' && empty($data['replied_at'])) {
            $data['replied_at'] = now();
            $data['read_at'] = $data['read_at'] ?? $contact->read_at ?? now();
        }

        $contact->update($data);

        return redirect()
            ->route('admin.contacts.index')
            ->with('status', 'Contact message updated successfully.');
    }

    public function destroy(ContactMessage $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()
            ->route('admin.contacts.index')
            ->with('status', 'Contact message deleted successfully.');
    }
}
