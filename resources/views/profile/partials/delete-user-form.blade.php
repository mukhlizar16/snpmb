<p style="font-size:.8125rem;color:#64748b;margin:0 0 16px;line-height:1.6;">
    Setelah akun dihapus, semua data dan resource akan <strong style="color:#dc2626;">dihapus secara permanen</strong>. Pastikan Anda telah mengunduh semua data penting sebelum melanjutkan.
</p>

<button
    class="btn-danger"
    x-data=""
    x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
>
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:5px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
    Hapus Akun Saya
</button>

<x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
    <form method="post" action="{{ route('profile.destroy') }}" style="padding:28px;">
        @csrf
        @method('delete')

        <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:16px;">
            <div style="width:40px;height:40px;border-radius:10px;background:#fee2e2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="20" height="20" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div>
                <h2 style="font-size:.9375rem;font-weight:700;color:#0f172a;margin:0 0 4px;">
                    Hapus akun secara permanen?
                </h2>
                <p style="font-size:.75rem;color:#64748b;margin:0;line-height:1.5;">
                    Semua data Anda akan dihapus dan tidak dapat dipulihkan. Masukkan password untuk konfirmasi.
                </p>
            </div>
        </div>

        <div style="margin-bottom:20px;">
            <label for="delete_password" style="display:block;font-size:.75rem;font-weight:600;color:#475569;margin-bottom:5px;">
                Password
            </label>
            <input
                id="delete_password" name="password" type="password"
                class="profile-input"
                placeholder="Masukkan password Anda"
                style="width:100%;"
            >
            @if ($errors->userDeletion->get('password'))
                <div class="profile-input-error">{{ implode(', ', $errors->userDeletion->get('password')) }}</div>
            @endif
        </div>

        <div style="display:flex;justify-content:flex-end;gap:10px;">
            <button type="button" class="btn-ghost" x-on:click="$dispatch('close')">
                Batal
            </button>
            <button type="submit" class="btn-danger">
                Ya, Hapus Akun
            </button>
        </div>
    </form>
</x-modal>
