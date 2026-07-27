<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportConversation;
use App\Models\SupportMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportChatController extends Controller
{
    public function index(): View
    {
        $conversations = SupportConversation::query()
            ->with(['user:id,name,email', 'recipientUser:id,name,email', 'property:id,title,slug', 'latestMessage'])
            ->latest('last_message_at')
            ->paginate(15);

        return view('Admin.support_chats.index', compact('conversations'));
    }

    public function show(SupportConversation $supportChat): View
    {
        $supportChat->load([
            'user:id,name,email,phone',
            'recipientUser:id,name,email,phone',
            'property:id,title,slug,listing_type,price',
            'messages',
        ]);

        SupportMessage::query()
            ->where('support_conversation_id', $supportChat->id)
            ->where('sender_type', 'user')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('Admin.support_chats.show', [
            'conversation' => $supportChat,
        ]);
    }

    public function reply(Request $request, SupportConversation $supportChat): RedirectResponse
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $supportChat->messages()->create([
            'sender_type' => 'admin',
            'sender_id' => auth('admin')->id(),
            'message' => $data['message'],
        ]);

        $supportChat->update([
            'admin_id' => auth('admin')->id(),
            'status' => 'open',
            'last_message_at' => now(),
        ]);

        return redirect()
            ->route('admin.support-chats.show', $supportChat)
            ->with('status', 'Reply sent successfully.');
    }

    public function updateStatus(Request $request, SupportConversation $supportChat): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:open,pending,closed'],
        ]);

        $supportChat->update([
            'status' => $data['status'],
            'closed_at' => $data['status'] === 'closed' ? now() : null,
        ]);

        return redirect()
            ->route('admin.support-chats.show', $supportChat)
            ->with('status', 'Conversation status updated.');
    }
}
