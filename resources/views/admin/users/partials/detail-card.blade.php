<div class="card h-100">
  <div class="card-header">
    <h3 class="card-title">{{ $title }}</h3>
  </div>
  <div class="card-body">
    <dl class="row mb-0">
      @foreach ($fields as $label => $value)
        <dt class="col-sm-4">{{ $label }}</dt>
        <dd class="col-sm-8 text-break">{{ filled($value) ? $value : 'Not set' }}</dd>
      @endforeach
    </dl>
  </div>
</div>
