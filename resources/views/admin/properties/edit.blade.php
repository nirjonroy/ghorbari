@extends('Admin.layouts.master')

@section('title', 'Edit Property')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Edit Property</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('admin.properties.index') }}">Properties</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">
            <form method="POST" action="{{ route('admin.properties.update', $property) }}" enctype="multipart/form-data">
              @method('PUT')
              @include('Admin.properties.partials.form', ['title' => 'Property Information'])
            </form>

            @if ($property->media->isNotEmpty())
              <div class="card mt-3">
                <div class="card-header"><h3 class="card-title">Media</h3></div>
                <div class="card-body">
                  <div class="row g-3">
                    @foreach ($property->media as $media)
                      <div class="col-md-3">
                        <div class="border rounded p-2 h-100">
                          @if ($media->media_type === 'image')
                            <img src="{{ asset($media->file_path) }}" alt="{{ $media->alt_text }}" class="img-fluid rounded mb-2">
                          @else
                            <div class="text-secondary mb-2">{{ strtoupper($media->media_type) }}: {{ basename($media->file_path) }}</div>
                          @endif
                          <form method="POST" action="{{ route('admin.property-media.destroy', $media) }}" onsubmit="return confirm('Delete this media?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Delete</button>
                          </form>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            @endif
          </div>
        </div>
      </main>
@endsection
