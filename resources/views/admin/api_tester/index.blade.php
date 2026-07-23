@extends('Admin.layouts.master')

@section('title', 'API Tester')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">API Tester</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">API Tester</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <div class="row g-3">
              <div class="col-xl-8">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Request Builder</h3>
                  </div>
                  <div class="card-body">
                    <div class="row g-3">
                      <div class="col-md-3">
                        <label for="api-method" class="form-label">Method</label>
                        <select id="api-method" class="form-select">
                          <option>GET</option>
                          <option>POST</option>
                          <option>PUT</option>
                          <option>PATCH</option>
                          <option>DELETE</option>
                        </select>
                      </div>
                      <div class="col-md-9">
                        <label for="api-path" class="form-label">Endpoint</label>
                        <input id="api-path" type="text" class="form-control" value="/api/admin/dashboard" />
                      </div>
                      <div class="col-md-12">
                        <label for="api-token" class="form-label">Bearer Token</label>
                        <div class="input-group">
                          <input id="api-token" type="password" class="form-control" placeholder="Paste token from /api/admin/login response" />
                          <button id="toggle-token" class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-eye"></i>
                          </button>
                          <button id="save-token" class="btn btn-outline-primary" type="button">Save</button>
                          <button id="clear-token" class="btn btn-outline-danger" type="button">Clear</button>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <label for="api-body" class="form-label">JSON Body</label>
                        <textarea id="api-body" class="form-control font-monospace" rows="12" spellcheck="false">{}</textarea>
                      </div>
                    </div>
                  </div>
                  <div class="card-footer d-flex justify-content-end gap-2">
                    <button id="format-json" type="button" class="btn btn-secondary">
                      <i class="bi bi-braces"></i> Format JSON
                    </button>
                    <button id="send-request" type="button" class="btn btn-primary">
                      <i class="bi bi-send"></i> Send Request
                    </button>
                  </div>
                </div>
              </div>

              <div class="col-xl-4">
                <div class="card mb-3">
                  <div class="card-header">
                    <h3 class="card-title">Quick Examples</h3>
                  </div>
                  <div class="list-group list-group-flush">
                    @foreach($examples as $label => $example)
                      <button
                        type="button"
                        class="list-group-item list-group-item-action api-example"
                        data-method="{{ $example['method'] }}"
                        data-path="{{ $example['path'] }}"
                        data-body='@json($example['body'] ?? new stdClass())'
                      >
                        <span class="badge text-bg-secondary me-2">{{ $example['method'] }}</span>{{ $label }}
                      </button>
                    @endforeach
                  </div>
                </div>

                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Resources</h3>
                  </div>
                  <div class="card-body">
                    <div class="row g-2">
                      @foreach($resources as $resource)
                        <div class="col-6">
                          <button type="button" class="btn btn-outline-secondary w-100 api-resource" data-resource="{{ $resource }}">
                            {{ $resource }}
                          </button>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>

                <div class="card mt-3">
                  <div class="card-header">
                    <h3 class="card-title">Frontend APIs</h3>
                  </div>
                  <div class="card-body">
                    @foreach($frontendApis as $group => $endpoints)
                      <h6 class="text-uppercase text-muted small fw-bold mt-2 mb-2">{{ $group }}</h6>
                      <div class="list-group mb-3">
                        @foreach($endpoints as $endpoint)
                          <button
                            type="button"
                            class="list-group-item list-group-item-action api-endpoint"
                            data-method="{{ $endpoint['method'] }}"
                            data-path="{{ $endpoint['path'] }}"
                          >
                            <span class="badge text-bg-success me-2">{{ $endpoint['method'] }}</span>
                            <span class="fw-semibold">{{ $endpoint['label'] }}</span>
                            <small class="d-block text-muted mt-1">{{ $endpoint['path'] }}</small>
                          </button>
                        @endforeach
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>

            <div class="card mt-3">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title mb-0">Response</h3>
                <span id="response-status" class="badge text-bg-secondary ms-auto">Waiting</span>
              </div>
              <div class="card-body">
                <pre id="api-response" class="bg-dark text-light p-3 rounded mb-0" style="min-height: 260px; white-space: pre-wrap;">Send a request to see the response.</pre>
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const method = document.getElementById('api-method');
  const path = document.getElementById('api-path');
  const token = document.getElementById('api-token');
  const body = document.getElementById('api-body');
  const response = document.getElementById('api-response');
  const status = document.getElementById('response-status');
  const savedToken = localStorage.getItem('admin_api_token');

  if (savedToken) {
    token.value = savedToken;
  }

  document.querySelectorAll('.api-example').forEach((button) => {
    button.addEventListener('click', function () {
      method.value = this.dataset.method;
      path.value = this.dataset.path;
      body.value = JSON.stringify(JSON.parse(this.dataset.body || '{}'), null, 2);
    });
  });

  document.querySelectorAll('.api-resource').forEach((button) => {
    button.addEventListener('click', function () {
      method.value = 'GET';
      path.value = '/api/admin/' + this.dataset.resource;
      body.value = '{}';
    });
  });

  document.querySelectorAll('.api-endpoint').forEach((button) => {
    button.addEventListener('click', function () {
      method.value = this.dataset.method;
      path.value = this.dataset.path;
      body.value = '{}';
    });
  });

  document.getElementById('toggle-token').addEventListener('click', function () {
    token.type = token.type === 'password' ? 'text' : 'password';
  });

  document.getElementById('save-token').addEventListener('click', function () {
    localStorage.setItem('admin_api_token', token.value.trim());
  });

  document.getElementById('clear-token').addEventListener('click', function () {
    token.value = '';
    localStorage.removeItem('admin_api_token');
  });

  document.getElementById('format-json').addEventListener('click', function () {
    try {
      body.value = JSON.stringify(JSON.parse(body.value || '{}'), null, 2);
    } catch (error) {
      response.textContent = error.message;
      status.textContent = 'Invalid JSON';
      status.className = 'badge text-bg-danger ms-auto';
    }
  });

  document.getElementById('send-request').addEventListener('click', async function () {
    const selectedMethod = method.value;
    const headers = {
      Accept: 'application/json',
    };
    const options = {
      method: selectedMethod,
      headers,
    };

    if (token.value.trim()) {
      headers.Authorization = 'Bearer ' + token.value.trim();
    }

    if (! ['GET', 'DELETE'].includes(selectedMethod)) {
      headers['Content-Type'] = 'application/json';

      try {
        options.body = JSON.stringify(JSON.parse(body.value || '{}'));
      } catch (error) {
        response.textContent = error.message;
        status.textContent = 'Invalid JSON';
        status.className = 'badge text-bg-danger ms-auto';
        return;
      }
    }

    response.textContent = 'Loading...';
    status.textContent = 'Sending';
    status.className = 'badge text-bg-info ms-auto';

    try {
      const result = await fetch(path.value, options);
      const text = await result.text();
      let output = text;

      try {
        output = JSON.stringify(JSON.parse(text), null, 2);
      } catch (error) {
        output = text || '(empty response)';
      }

      response.textContent = output;
      status.textContent = result.status + ' ' + result.statusText;
      status.className = result.ok ? 'badge text-bg-success ms-auto' : 'badge text-bg-danger ms-auto';
    } catch (error) {
      response.textContent = error.message;
      status.textContent = 'Failed';
      status.className = 'badge text-bg-danger ms-auto';
    }
  });
});
</script>
@endpush
