@extends('Admin.layouts.master')

@section('title', 'Edit Contact')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Edit Contact</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('admin.contacts.index') }}">Contacts</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <form method="POST" action="{{ route('admin.contacts.update', $contact) }}">
              @csrf
              @method('PUT')

              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Contact Information</h3>
                </div>
                <div class="card-body">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label for="name" class="form-label">Name</label>
                      <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $contact->name) }}" required>
                      @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                      <label for="status" class="form-label">Status</label>
                      <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach(['new' => 'New', 'read' => 'Read', 'replied' => 'Replied', 'closed' => 'Closed', 'spam' => 'Spam'] as $value => $label)
                          <option value="{{ $value }}" @selected(old('status', $contact->status) === $value)>{{ $label }}</option>
                        @endforeach
                      </select>
                      @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                      <label for="email" class="form-label">Email</label>
                      <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $contact->email) }}">
                      @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                      <label for="phone" class="form-label">Phone</label>
                      <input id="phone" type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $contact->phone) }}">
                      @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                      <label for="subject" class="form-label">Subject</label>
                      <input id="subject" type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject', $contact->subject) }}">
                      @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                      <label for="message_type" class="form-label">Message Type</label>
                      <input id="message_type" type="text" name="message_type" class="form-control @error('message_type') is-invalid @enderror" value="{{ old('message_type', $contact->message_type) }}">
                      @error('message_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                      <label for="message" class="form-label">Message</label>
                      <textarea id="message" name="message" rows="6" class="form-control @error('message') is-invalid @enderror" required>{{ old('message', $contact->message) }}</textarea>
                      @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                      <label for="source_page" class="form-label">Source Page</label>
                      <input id="source_page" type="text" name="source_page" class="form-control @error('source_page') is-invalid @enderror" value="{{ old('source_page', $contact->source_page) }}">
                      @error('source_page')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                      <label for="read_at" class="form-label">Read At</label>
                      <input id="read_at" type="datetime-local" name="read_at" class="form-control @error('read_at') is-invalid @enderror" value="{{ old('read_at', optional($contact->read_at)->format('Y-m-d\TH:i')) }}">
                      @error('read_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                      <label for="replied_at" class="form-label">Replied At</label>
                      <input id="replied_at" type="datetime-local" name="replied_at" class="form-control @error('replied_at') is-invalid @enderror" value="{{ old('replied_at', optional($contact->replied_at)->format('Y-m-d\TH:i')) }}">
                      @error('replied_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                      <label for="admin_note" class="form-label">Admin Note</label>
                      <textarea id="admin_note" name="admin_note" rows="4" class="form-control @error('admin_note') is-invalid @enderror">{{ old('admin_note', $contact->admin_note) }}</textarea>
                      @error('admin_note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                  </div>
                </div>
                <div class="card-footer d-flex justify-content-end gap-2">
                  <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">Cancel</a>
                  <button type="submit" class="btn btn-primary">Save Contact</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </main>
@endsection
