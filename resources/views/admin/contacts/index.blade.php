@extends('Admin.layouts.master')

@section('title', 'Contacts')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Contacts</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Contacts</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Contact Message List</h3>
              </div>
              <div class="card-body">
                @if (session('status'))
                  <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                @endif

                <div class="table-responsive">
                  <table class="table table-bordered table-striped align-middle">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th style="width: 150px">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($contacts as $contact)
                        <tr>
                          <td>
                            <div>{{ $contact->name }}</div>
                            @if($contact->user)
                              <div class="text-secondary small">User: {{ $contact->user->name }}</div>
                            @endif
                          </td>
                          <td>
                            <div>{{ $contact->email ?? 'No email' }}</div>
                            <div class="text-secondary small">{{ $contact->phone ?? 'No phone' }}</div>
                          </td>
                          <td>{{ $contact->subject ?? 'Not set' }}</td>
                          <td>{{ Str::limit($contact->message, 80) }}</td>
                          <td>
                            <span class="badge {{ match($contact->status) {
                              'new' => 'text-bg-primary',
                              'read' => 'text-bg-info',
                              'replied' => 'text-bg-success',
                              'closed' => 'text-bg-secondary',
                              'spam' => 'text-bg-danger',
                              default => 'text-bg-secondary',
                            } }}">
                              {{ ucfirst($contact->status) }}
                            </span>
                          </td>
                          <td>{{ optional($contact->created_at)->format('d M Y') }}</td>
                          <td>
                            <div class="d-flex gap-1">
                              <a href="{{ route('admin.contacts.edit', $contact) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                              <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" onsubmit="return confirm('Delete this contact message?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                              </form>
                            </div>
                          </td>
                        </tr>
                      @empty
                        <tr><td colspan="7" class="text-center text-secondary">No contact messages found.</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>

                {{ $contacts->links() }}
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection
