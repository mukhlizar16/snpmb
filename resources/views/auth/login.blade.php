<x-guest-layout>

    {{-- Session status (e.g. after password reset) --}}
    @if (session('status'))
        <div class="auth-status-msg">{{ session('status') }}</div>
    @endif

    <h1 class="auth-heading">Masuk ke Sistem</h1>
    <p class="auth-subheading">Masukkan kredensial Anda untuk melanjutkan.</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="auth-field">
            <label class="auth-label" for="email">Alamat Email</label>
            <input id="email" class="auth-input {{ $errors->has('email') ? 'is-error' : '' }}" type="email"
                name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus
                autocomplete="username">
            @foreach ($errors->get('email') as $msg)
                <div class="auth-error-msg">{{ $msg }}</div>
            @endforeach
        </div>

        {{-- Password --}}
        <div class="auth-field">
            <label class="auth-label" for="password">Password</label>
            <input id="password" class="auth-input {{ $errors->has('password') ? 'is-error' : '' }}" type="password"
                name="password" placeholder="••••••••" required autocomplete="current-password">
            @foreach ($errors->get('password') as $msg)
                <div class="auth-error-msg">{{ $msg }}</div>
            @endforeach
        </div>

        {{-- Remember me + Forgot password --}}
        <div class="auth-row-between">
            <label class="auth-check-label">
                <input type="checkbox" name="remember">
                <span class="auth-check-text">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <button type="submit" class="auth-submit">
            Masuk
        </button>
    </form>

    @if (Route::has('register'))
        <div class="auth-alt-link">
            Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
        </div>
    @endif

</x-guest-layout>
