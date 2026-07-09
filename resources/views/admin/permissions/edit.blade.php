@extends('Admin.layouts.master')

@section('title', 'Edit Permission')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Edit Permission</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('admin.permissions.index') }}">Permissions</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">
            <form method="POST" action="{{ route('admin.permissions.update', $permission) }}">
              @method('PUT')
              @include('Admin.permissions.partials.form', ['title' => 'Permission Information'])
            </form>
          </div>
        </div>
      </main>
@endsection
