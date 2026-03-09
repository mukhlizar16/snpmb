<form method="post" action="{{ route('password.update') }}" style="display:flex;flex-direction:column;gap:18px;">
    @csrf
    @method('put')

    <div>
        <label for="update_password_current_password" class="profile-label">Password Saat Ini</label>
        <input
            id="update_password_current_password" name="current_password" type="password"
            class="profile-input"
            autocomplete="current-password"
        >
        @if ($errors->updatePassword->get('current_password'))
            <div class="profile-input-error">{{ implode(', ', $errors->updatePassword->get('current_password')) }}</div>
        @endif
    </div>

    <div>
        <label for="update_password_password" class="profile-label">Password Baru</label>
        <input
            id="update_password_password" name="password" type="password"
            class="profile-input"
            autocomplete="new-password"
        >
        @if ($errors->updatePassword->get('password'))
            <div class="profile-input-error">{{ implode(', ', $errors->updatePassword->get('password')) }}</div>
        @endif
    </div>

    <div>
        <label for="update_password_password_confirmation" class="profile-label">Konfirmasi Password Baru</label>
        <input
            id="update_password_password_confirmation" name="password_confirmation" type="password"
            class="profile-input"
            autocomplete="new-password"
        >
        @if ($errors->updatePassword->get('password_confirmation'))
            <div class="profile-input-error">{{ implode(', ', $errors->updatePassword->get('password_confirmation')) }}</div>
        @endif
    </div>

    <div style="display:flex;align-items:center;gap:12px;padding-top:4px;">
        <button type="submit" class="btn-primary" style="background:#16a34a;" onmouseover="this.style.background='#15803d'" onmouseout="this.style.background='#16a34a'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Perbarui Password
        </button>

        @if (session('status') === 'password-updated')
            <span
                class="save-feedback"
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2500)"
            >
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                Password diperbarui
            </span>
        @endif
    </div>
</form>
