@extends('Admin.layouts.master')

@section('title', 'Create Slider')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Create Slider</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('admin.sliders.index') }}">Sliders</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <form method="POST" action="{{ route('admin.sliders.store') }}" enctype="multipart/form-data">
              @csrf
              @include('Admin.sliders.partials.form', ['slider' => $slider, 'buttonText' => 'Create Slider'])
            </form>
          </div>
        </div>
      </main>
@endsection
