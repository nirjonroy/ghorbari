@extends('Admin.layouts.master')

@section('title', 'Add District')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Add District</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('admin.districts.index') }}">Districts</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Add</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <form method="POST" action="{{ route('admin.districts.store') }}" enctype="multipart/form-data">
              @include('Admin.districts.partials.form', ['title' => 'District Information'])
            </form>
          </div>
        </div>
      </main>
@endsection
