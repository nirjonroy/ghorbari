@extends('Admin.layouts.master')

@section('title', 'Support Chats')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Support Chats</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Support Chats</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Conversation List</h3>
              </div>
              <div class="card-body">
                @if (session('status'))
                  <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                @endif

                <div class="table-responsive">
                  <table class="table table-bordered table-striped align-middle">
                    <thead>
                      <tr>
                        <th>Subject</th>
                        <th>User</th>
                        <th>Target</th>
                        <th>Last Message</th>
                        <th>Status</th>
                        <th>Updated</th>
                        <th style="width: 90px">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($conversations as $conversation)
                        <tr>
                          <td>
                            <strong>{{ $conversation->subject ?: 'Support Chat' }}</strong>
                            @if($conversation->property)
                              <div class="small text-secondary">{{ $conversation->property->title }}</div>
                            @endif
                          </td>
                          <td>
                            <div>{{ $conversation->user?->name ?: 'Guest user' }}</div>
                            <div class="small text-secondary">{{ $conversation->user?->email }}</div>
                          </td>
                          <td>{{ ucfirst($conversation->target_type) }}</td>
                          <td>{{ Str::limit($conversation->latestMessage?->message ?: 'No messages yet', 70) }}</td>
                          <td>
                            <span class="badge {{ $conversation->status === 'closed' ? 'text-bg-secondary' : ($conversation->status === 'pending' ? 'text-bg-warning' : 'text-bg-success') }}">
                              {{ ucfirst($conversation->status) }}
                            </span>
                          </td>
                          <td>{{ optional($conversation->last_message_at ?: $conversation->updated_at)->format('d M Y h:i A') }}</td>
                          <td>
                            <a href="{{ route('admin.support-chats.show', $conversation) }}" class="btn btn-sm btn-primary">
                              <i class="bi bi-chat-dots"></i> Open
                            </a>
                          </td>
                        </tr>
                      @empty
                        <tr><td colspan="7" class="text-center text-secondary">No support conversations found.</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>

                {{ $conversations->links() }}
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection
