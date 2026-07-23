@extends('Admin.layouts.master')

@section('title', 'Add Agent Profile')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Add Agent Profile</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('admin.agent-profiles.index') }}">Agent Profiles</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Add</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">
            <form method="POST" action="{{ route('admin.agent-profiles.store') }}" enctype="multipart/form-data">
              @include('Admin.agent_profiles.partials.form', ['title' => 'Agent Information'])
            </form>
          </div>
        </div>
      </main>
@endsection
