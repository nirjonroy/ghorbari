@php
  $chatProperty = isset($property) && $property instanceof \App\Models\Property ? $property : null;
  $chatAgentUserId = $chatProperty?->agent?->user_id;
@endphp

<div
  class="support-chat-widget"
  data-support-chat
  data-authenticated="{{ auth()->check() ? '1' : '0' }}"
  data-login-url="{{ route('login') }}"
  data-store-url="{{ auth()->check() ? route('frontend.support-chats.store') : '' }}"
  data-messages-url-template="{{ auth()->check() ? url('/support-chats/__ID__/messages') : '' }}"
  data-reply-url-template="{{ auth()->check() ? url('/support-chats/__ID__/messages') : '' }}"
  data-property-id="{{ $chatProperty?->id }}"
  data-property-title="{{ $chatProperty?->title }}"
  data-owner-id="{{ $chatProperty?->owner_user_id }}"
  data-agent-id="{{ $chatAgentUserId }}"
>
  <button class="support-chat-launcher" type="button" data-support-chat-open aria-label="Open support chat">
    <i class="bi bi-chat-dots-fill"></i>
    <span>Support</span>
  </button>

  <section class="support-chat-panel" aria-label="Support chat panel" hidden>
    <header>
      <div>
        <span>Live Chat</span>
        <strong>Support Desk</strong>
      </div>
      <button type="button" data-support-chat-close aria-label="Close support chat"><i class="bi bi-x-lg"></i></button>
    </header>

    <div class="support-chat-body" data-support-chat-body>
      <div class="support-chat-empty">
        <i class="bi bi-headset"></i>
        <h3>How can we help?</h3>
        <p>Message support, a property owner, seller, or assigned agent.</p>
      </div>
    </div>

    @auth
      <form class="support-chat-form" data-support-chat-form>
        @csrf
        <input type="hidden" name="conversation_id" data-support-chat-conversation>
        <input type="hidden" name="property_id" value="{{ $chatProperty?->id }}" data-support-chat-property>
        <input type="hidden" name="target_type" value="admin" data-support-chat-target>
        <input type="hidden" name="recipient_user_id" value="" data-support-chat-recipient>
        <input type="hidden" name="subject" value="{{ $chatProperty?->title ?: 'Support Chat' }}" data-support-chat-subject>
        <textarea name="message" rows="3" placeholder="Write your message..." required></textarea>
        <button type="submit"><i class="bi bi-send"></i> Send</button>
      </form>
    @else
      <div class="support-chat-login">
        <p>Please sign in to start a secure chat.</p>
        <a href="{{ route('login') }}">Sign In</a>
      </div>
    @endauth
  </section>
</div>
