@extends('Frontend.layouts.master')

@section('title', 'Support Chats | Land Site')
@section('body_class', 'frontend-page user-dashboard-page')

@section('content')
@php
    $user = $dashboardData['user'];
    $profileCompletion = $dashboardData['profile_completion'];
    $avatar = $user->profile_photo_path ? asset($user->profile_photo_path) : asset('frontend/assets/images/avatar_1.jpg');
@endphp

<main class="dashboard-page">
  <section class="dashboard-shell">
    @include('User.partials.sidebar')

    <section class="dashboard-main">
      <div class="dashboard-topbar">
        <div>
          <p>Support Chat</p>
          <h2>Talk With Support, Owners, and Agents</h2>
        </div>
        <div class="dashboard-actions">
          <button class="btn btn-danger" type="button" data-support-chat-open>
            <i class="bi bi-chat-dots"></i> New Chat
          </button>
        </div>
      </div>

      <section class="dashboard-card">
        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>Conversation</th>
                <th>Property</th>
                <th>Target</th>
                <th>Status</th>
                <th>Updated</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @forelse($conversations as $conversation)
                <tr>
                  <td>
                    <strong>{{ $conversation->subject ?: 'Support Chat' }}</strong>
                    <div class="text-secondary small">{{ Str::limit($conversation->latestMessage?->message ?: 'No messages yet', 72) }}</div>
                  </td>
                  <td>{{ $conversation->property?->title ?: 'General support' }}</td>
                  <td>{{ ucfirst($conversation->target_type) }}</td>
                  <td><span class="badge text-bg-{{ $conversation->status === 'closed' ? 'secondary' : 'success' }}">{{ ucfirst($conversation->status) }}</span></td>
                  <td>{{ optional($conversation->last_message_at ?: $conversation->updated_at)->format('d M Y h:i A') }}</td>
                  <td class="text-end">
                    <button
                      class="btn btn-outline-dark btn-sm"
                      type="button"
                      data-support-chat-open
                      data-conversation-id="{{ $conversation->id }}"
                    >
                      Open
                    </button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center text-secondary py-4">
                    No support conversations yet. Start one from any property page or use the chat button.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{ $conversations->links() }}
      </section>
    </section>
  </section>
</main>
@endsection
