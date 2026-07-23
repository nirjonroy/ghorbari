<x-guest-layout>
    <div class="auth-form-head">
        <a href="{{ route('frontend.home') }}">Back to home</a>
        <h2>Create your account</h2>
        <p>Join to save homes, list properties, request tours, and manage your property workspace.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="auth-form-grid">
            <div>
                <label class="auth-label" for="name">{{ __('Name') }}</label>
                <input id="name" class="auth-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <label class="auth-label" for="email">{{ __('Email') }}</label>
                <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <label class="auth-label" for="password">{{ __('Password') }}</label>
                <input id="password" class="auth-input" type="password" name="password" required autocomplete="new-password">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <label class="auth-label" for="password_confirmation">{{ __('Confirm Password') }}</label>
                <input id="password_confirmation" class="auth-input" type="password" name="password_confirmation" required autocomplete="new-password">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="auth-actions">
            <span class="auth-switch">
                Already registered?
                <a class="auth-link" href="{{ route('login') }}">Sign in</a>
            </span>

            <button class="auth-submit" type="submit">
                {{ __('Create Account') }}
            </button>
        </div>
    </form>
</x-guest-layout>
