<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" style="display:flex;flex-direction:column;gap:18px;">
    @csrf
    @method('patch')

    <div>
        <label for="name" class="profile-label">Nama Lengkap</label>
        <input
            id="name" name="name" type="text"
            class="profile-input"
            value="{{ old('name', $user->name) }}"
            required autofocus autocomplete="name"
        >
        @if ($errors->get('name'))
            <div class="profile-input-error">{{ implode(', ', $errors->get('name')) }}</div>
        @endif
    </div>

    <div>
        <label for="email" class="profile-label">Alamat Email</label>
        <input
            id="email" name="email" type="email"
            class="profile-input"
            value="{{ old('email', $user->email) }}"
            required autocomplete="username"
        >
        @if ($errors->get('email'))
            <div class="profile-input-error">{{ implode(', ', $errors->get('email')) }}</div>
        @endif

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div style="margin-top:8px;padding:10px 12px;background:#fffbeb;border:1px solid #fde68a;border-radius:8px;">
                <p style="font-size:.75rem;color:#92400e;margin:0;">
                    Email Anda belum terverifikasi.
                    <button form="send-verification" style="background:none;border:none;color:#2563eb;cursor:pointer;font-size:.75rem;font-weight:600;padding:0;text-decoration:underline;">
                        Kirim ulang link verifikasi
                    </button>
                </p>
                @if (session('status') === 'verification-link-sent')
                    <p style="font-size:.75rem;color:#16a34a;margin:6px 0 0;font-weight:500;">
                        Link verifikasi baru telah dikirim ke email Anda.
                    </p>
                @endif
            </div>
        @endif
    </div>

    <div style="display:flex;align-items:center;gap:12px;padding-top:4px;">
        <button type="submit" class="btn-primary">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            Simpan Perubahan
        </button>

        @if (session('status') === 'profile-updated')
            <span
                class="save-feedback"
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2500)"
            >
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                Tersimpan
            </span>
        @endif
    </div>
</form>
