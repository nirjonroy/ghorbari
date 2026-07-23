@extends('Admin.layouts.master')

@section('title', 'Edit Property Type')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Edit Property Type</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('admin.property-types.index') }}">Property Types</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">
            <form method="POST" action="{{ route('admin.property-types.update', $propertyType) }}" enctype="multipart/form-data">
              @method('PUT')
              @include('Admin.property_types.partials.form', ['title' => 'Property Type Information'])
            </form>
          </div>
        </div>
      </main>
@endsection
