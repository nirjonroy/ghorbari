<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\SupportConversation;
use App\Models\SupportMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SupportChatController extends Controller
{
    public function index(Request $request): View
    {
        $conversations = SupportConversation::query()
            ->where(function ($query) use ($request) {
                $query->where('user_id', $request->user()->id)
                    ->orWhere('recipient_user_id', $request->user()->id);
            })
            ->with(['property:id,title,slug', 'latestMessage', 'user:id,name', 'recipientUser:id,name'])
            ->latest('last_message_at')
            ->paginate(12);

        return view('User.support_chats.index', [
            'dashboardData' => app(\App\Http\Controllers\User\UserController::class)->dashboardPayload($request),
            'conversations' => $conversations,
            'selectedConversation' => null,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'property_id' => ['nullable', 'integer', 'exists:properties,id'],
            'target_type' => ['required', Rule::in(['admin', 'owner', 'seller', 'agent'])],
            'recipient_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $property = isset($data['property_id'])
            ? Property::query()->with('agent.user:id,name')->find($data['property_id'])
            : null;

        $recipientUserId = $data['recipient_user_id'] ?? null;

        if ($property && ! $recipientUserId) {
            $recipientUserId = match ($data['target_type']) {
                'agent' => optional($property->agent)->user_id,
                'owner', 'seller' => $property->owner_user_id,
                default => null,
            };
        }

        $conversation = SupportConversation::create([
            'user_id' => $request->user()->id,
            'property_id' => $property?->id,
            'recipient_user_id' => $recipientUserId,
            'target_type' => $data['target_type'],
            'subject' => $data['subject'] ?? ($property?->title ?: 'Support Chat'),
            'status' => 'open',
            'last_message_at' => now(),
        ]);

        $conversation->messages()->create([
            'sender_type' => 'user',
            'sender_id' => $request->user()->id,
            'message' => $data['message'],
        ]);

        $conversation->load(['property:id,title,slug', 'messages']);

        return response()->json([
            'status' => 'ok',
            'conversation' => $this->conversationPayload($conversation, $request),
        ], 201);
    }

    public function messages(Request $request, SupportConversation $supportChat): JsonResponse
    {
        $this->authorizeParticipant($request, $supportChat);
        $supportChat->load(['property:id,title,slug', 'messages']);

        return response()->json([
            'status' => 'ok',
            'conversation' => $this->conversationPayload($supportChat, $request),
        ]);
    }

    public function reply(Request $request, SupportConversation $supportChat): JsonResponse
    {
        $this->authorizeParticipant($request, $supportChat);

        $data = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $supportChat->messages()->create([
            'sender_type' => 'user',
            'sender_id' => $request->user()->id,
            'message' => $data['message'],
        ]);

        $supportChat->update([
            'status' => 'open',
            'last_message_at' => now(),
        ]);

        $supportChat->load(['property:id,title,slug', 'messages']);

        return response()->json([
            'status' => 'ok',
            'conversation' => $this->conversationPayload($supportChat, $request),
        ]);
    }

    private function authorizeParticipant(Request $request, SupportConversation $conversation): void
    {
        abort_unless(
            $conversation->user_id === $request->user()->id || $conversation->recipient_user_id === $request->user()->id,
            403
        );
    }

    private function conversationPayload(SupportConversation $conversation, Request $request): array
    {
        return [
            'id' => $conversation->id,
            'subject' => $conversation->subject,
            'status' => $conversation->status,
            'property' => $conversation->property ? [
                'id' => $conversation->property->id,
                'title' => $conversation->property->title,
            ] : null,
            'messages' => $conversation->messages->map(function (SupportMessage $message) use ($request) {
                return [
                    'id' => $message->id,
                    'sender_type' => $message->sender_type,
                    'own' => $message->sender_type === 'user' && $message->sender_id === $request->user()->id,
                    'message' => $message->message,
                    'time' => optional($message->created_at)->format('d M Y h:i A'),
                ];
            })->values(),
        ];
    }
}
