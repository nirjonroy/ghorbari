@extends('Admin.layouts.master')

@section('title', 'Support Chat')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Support Chat</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('admin.support-chats.index') }}">Support Chats</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Conversation</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            @if(session('status'))
              <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <div class="row g-3">
              <div class="col-lg-8">
                <div class="card">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                      <h3 class="card-title mb-0">{{ $conversation->subject ?: 'Support Chat' }}</h3>
                      <div class="small text-secondary">
                        {{ ucfirst($conversation->target_type) }} conversation
                        @if($conversation->property)
                          for {{ $conversation->property->title }}
                        @endif
                      </div>
                    </div>
                    <span class="badge {{ $conversation->status === 'closed' ? 'text-bg-secondary' : ($conversation->status === 'pending' ? 'text-bg-warning' : 'text-bg-success') }}">
                      {{ ucfirst($conversation->status) }}
                    </span>
                  </div>
                  <div class="card-body">
                    <div class="support-thread border rounded p-3 bg-body-tertiary" style="max-height: 520px; overflow-y: auto;">
                      @foreach($conversation->messages as $message)
                        <div class="mb-3 {{ $message->sender_type === 'admin' ? 'text-end' : '' }}">
                          <div class="d-inline-block p-3 rounded {{ $message->sender_type === 'admin' ? 'text-bg-primary' : 'bg-white border' }}" style="max-width: 78%;">
                            <div class="small fw-semibold mb-1">{{ $message->sender_type === 'admin' ? 'Admin' : 'User' }}</div>
                            <div>{{ $message->message }}</div>
                            <div class="small opacity-75 mt-2">{{ optional($message->created_at)->format('d M Y h:i A') }}</div>
                          </div>
                        </div>
                      @endforeach
                    </div>

                    <form class="mt-3" method="POST" action="{{ route('admin.support-chats.reply', $conversation) }}">
                      @csrf
                      <label class="form-label" for="message">Reply</label>
                      <textarea id="message" name="message" class="form-control @error('message') is-invalid @enderror" rows="4" required>{{ old('message') }}</textarea>
                      @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                      <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('admin.support-chats.index') }}" class="btn btn-secondary">Back</a>
                        <button type="submit" class="btn btn-primary">Send Reply</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <div class="col-lg-4">
                <div class="card">
                  <div class="card-header"><h3 class="card-title">Conversation Details</h3></div>
                  <div class="card-body">
                    <dl class="row mb-0">
                      <dt class="col-sm-5">User</dt>
                      <dd class="col-sm-7">{{ $conversation->user?->name ?: 'Not set' }}<br><small class="text-secondary">{{ $conversation->user?->email }}</small></dd>
                      <dt class="col-sm-5">Recipient</dt>
                      <dd class="col-sm-7">{{ $conversation->recipientUser?->name ?: 'Admin support' }}</dd>
                      <dt class="col-sm-5">Property</dt>
                      <dd class="col-sm-7">{{ $conversation->property?->title ?: 'General support' }}</dd>
                      <dt class="col-sm-5">Created</dt>
                      <dd class="col-sm-7">{{ optional($conversation->created_at)->format('d M Y h:i A') }}</dd>
                    </dl>

                    <form class="mt-3" method="POST" action="{{ route('admin.support-chats.status', $conversation) }}">
                      @csrf
                      @method('PATCH')
                      <label class="form-label" for="status">Status</label>
                      <select id="status" name="status" class="form-select">
                        @foreach(['open', 'pending', 'closed'] as $status)
                          <option value="{{ $status }}" @selected($conversation->status === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                      </select>
                      <button type="submit" class="btn btn-outline-primary w-100 mt-3">Update Status</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection
