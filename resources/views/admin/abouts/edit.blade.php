@extends('Admin.layouts.master')

@section('title', 'Edit About Content')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Edit About Content</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('admin.abouts.index') }}">About Us</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">
            <form method="POST" action="{{ route('admin.abouts.update', $about) }}" enctype="multipart/form-data">
              @method('PUT')
              @include('Admin.abouts.partials.form', ['title' => 'About Information'])
            </form>
          </div>
        </div>
      </main>
@endsection
