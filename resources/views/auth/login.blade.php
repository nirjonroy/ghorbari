<x-guest-layout>
    <div class="auth-form-head">
        <a href="{{ route('frontend.home') }}">Back to home</a>
        <h2>Welcome back</h2>
        <p>Sign in to manage saved searches, properties, appointments, and billing.</p>
    </div>

    @if (session('status'))
        <div class="auth-status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="auth-form-grid">
            <div>
                <label class="auth-label" for="email">{{ __('Email') }}</label>
                <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <label class="auth-label" for="password">{{ __('Password') }}</label>
                <input id="password" class="auth-input" type="password" name="password" required autocomplete="current-password">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="auth-options">
                <label for="remember_me" class="auth-checkbox">
                    <input id="remember_me" type="checkbox" name="remember">
                    <span>{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="auth-link" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="auth-actions">
            <span class="auth-switch">
                New here?
                <a class="auth-link" href="{{ route('register') }}">Create account</a>
            </span>

            <button class="auth-submit" type="submit">
                {{ __('Sign In') }}
            </button>
        </div>
    </form>
</x-guest-layout>
