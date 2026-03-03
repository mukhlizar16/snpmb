<x-guest-layout>

    <h1 class="auth-heading">Buat Akun</h1>
    <p class="auth-subheading">Isi data berikut untuk membuat akun baru.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Name --}}
        <div class="auth-field">
            <label class="auth-label" for="name">Nama Lengkap</label>
            <input
                id="name"
                class="auth-input {{ $errors->has('name') ? 'is-error' : '' }}"
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="Nama Anda"
                required
                autofocus
                autocomplete="name"
            >
            @foreach ($errors->get('name') as $msg)
                <div class="auth-error-msg">{{ $msg }}</div>
            @endforeach
        </div>

        {{-- Email --}}
        <div class="auth-field">
            <label class="auth-label" for="email">Alamat Email</label>
            <input
                id="email"
                class="auth-input {{ $errors->has('email') ? 'is-error' : '' }}"
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="nama@email.com"
                required
                autocomplete="username"
            >
            @foreach ($errors->get('email') as $msg)
                <div class="auth-error-msg">{{ $msg }}</div>
            @endforeach
        </div>

        {{-- Password --}}
        <div class="auth-field">
            <label class="auth-label" for="password">Password</label>
            <input
                id="password"
                class="auth-input {{ $errors->has('password') ? 'is-error' : '' }}"
                type="password"
                name="password"
                placeholder="Min. 8 karakter"
                required
                autocomplete="new-password"
            >
            @foreach ($errors->get('password') as $msg)
                <div class="auth-error-msg">{{ $msg }}</div>
            @endforeach
        </div>

        {{-- Confirm password --}}
        <div class="auth-field" style="margin-bottom:24px;">
            <label class="auth-label" for="password_confirmation">Konfirmasi Password</label>
            <input
                id="password_confirmation"
                class="auth-input {{ $errors->has('password_confirmation') ? 'is-error' : '' }}"
                type="password"
                name="password_confirmation"
                placeholder="Ulangi password"
                required
                autocomplete="new-password"
            >
            @foreach ($errors->get('password_confirmation') as $msg)
                <div class="auth-error-msg">{{ $msg }}</div>
            @endforeach
        </div>

        <button type="submit" class="auth-submit">
            Buat Akun
        </button>
    </form>

    <div class="auth-alt-link">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
    </div>

</x-guest-layout>
