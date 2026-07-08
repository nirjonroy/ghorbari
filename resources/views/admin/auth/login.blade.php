<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} | Admin Login</title>

        <link rel="preload" href="{{ asset('admin/css/adminlte.min.css') }}" as="style">
        <link rel="stylesheet" href="{{ asset('admin/css/adminlte.min.css') }}">
        <style>
            body {
                font-family: "Source Sans 3", system-ui, -apple-system, "Segoe UI", sans-serif;
            }

            .login-box {
                width: 360px;
            }

            .login-page {
                min-height: 100vh;
            }
        </style>
    </head>
    <body class="login-page bg-body-secondary">
        <div class="login-box">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <a href="{{ route('admin.login') }}" class="link-dark text-center link-offset-2 link-opacity-100 link-opacity-50-hover">
                        <h1 class="mb-0"><b>Ghorbari</b> Admin</h1>
                    </a>
                </div>

                <div class="card-body login-card-body">
                    <p class="login-box-msg">Sign in to start your session</p>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login') }}">
                        @csrf

                        <div class="input-group mb-1">
                            <div class="form-floating">
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
                                    placeholder="Email"
                                    required
                                    autofocus
                                    autocomplete="username"
                                >
                                <label for="email">Email</label>
                            </div>
                            <div class="input-group-text"><span>@</span></div>
                        </div>
                        @error('email')
                            <div class="text-danger small mb-2">{{ $message }}</div>
                        @enderror

                        <div class="input-group mb-1">
                            <div class="form-floating">
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Password"
                                    required
                                    autocomplete="current-password"
                                >
                                <label for="password">Password</label>
                            </div>
                            <div class="input-group-text"><span>&#128274;</span></div>
                        </div>
                        @error('password')
                            <div class="text-danger small mb-2">{{ $message }}</div>
                        @enderror

                        <div class="row">
                            <div class="col-8 d-inline-flex align-items-center">
                                <div class="form-check">
                                    <input id="remember" type="checkbox" class="form-check-input" name="remember">
                                    <label class="form-check-label" for="remember">Remember Me</label>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">Sign In</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <p class="mb-1 mt-3">
                        <a href="{{ route('admin.password.request') }}">I forgot my password</a>
                    </p>
                    <p class="mb-0">
                        <a href="{{ route('admin.register') }}" class="text-center">Register a new admin</a>
                    </p>
                </div>
            </div>
        </div>

        <script src="{{ asset('admin/js/adminlte.min.js') }}"></script>
    </body>
</html>
