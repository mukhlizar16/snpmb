<x-app-layout>
    <x-slot name="header">
        <span style="font-size:.9375rem;font-weight:700;color:#0f172a;font-family:'IBM Plex Mono',monospace;">
            Pengaturan Akun
        </span>
        <span style="font-size:.75rem;color:#94a3b8;margin-left:8px;">
            Kelola informasi profil dan keamanan akun
        </span>
    </x-slot>

    @push('styles')
    <style>
        .profile-grid {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 24px;
            align-items: start;
        }
        @media (max-width: 900px) {
            .profile-grid { grid-template-columns: 1fr; }
        }
        .profile-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 1px 4px rgba(15,23,42,.04);
            overflow: hidden;
        }
        .profile-card-header {
            padding: 20px 20px 16px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .profile-card-header .section-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .profile-card-header h3 {
            font-size: .875rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }
        .profile-card-header p {
            font-size: .7rem;
            color: #94a3b8;
            margin: 2px 0 0;
        }
        .profile-card-body { padding: 20px; }
        .profile-label {
            display: block;
            font-size: .75rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 5px;
            letter-spacing: .03em;
        }
        .profile-input {
            width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 9px 12px;
            font-size: .8125rem;
            color: #0f172a;
            background: #f8fafc;
            transition: border-color .15s, box-shadow .15s;
            outline: none;
            box-sizing: border-box;
        }
        .profile-input:focus {
            border-color: #2563eb;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
        }
        .profile-input-error {
            font-size: .7rem;
            color: #ef4444;
            margin-top: 4px;
        }
        .btn-primary {
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 18px;
            font-size: .8125rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s, transform .1s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-primary:active { transform: scale(.98); }
        .btn-danger {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 8px 18px;
            font-size: .8125rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s;
        }
        .btn-danger:hover { background: #fecaca; }
        .btn-ghost {
            background: transparent;
            color: #64748b;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 18px;
            font-size: .8125rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s;
        }
        .btn-ghost:hover { background: #f1f5f9; }
        .save-feedback {
            font-size: .75rem;
            color: #16a34a;
            display: flex;
            align-items: center;
            gap: 4px;
        }
    </style>
    @endpush

    <div style="max-width:80rem;margin:0 auto;padding:28px 1.5rem;">
        <div class="profile-grid">

            {{-- ── Sidebar: Info Akun ────────────────────────────────── --}}
            <div style="display:flex;flex-direction:column;gap:16px;">

                {{-- Avatar + info --}}
                <div class="profile-card">
                    <div style="padding:24px;display:flex;flex-direction:column;align-items:center;text-align:center;gap:12px;">
                        {{-- Avatar initials --}}
                        <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#1d4ed8);display:flex;align-items:center;justify-content:center;font-size:1.625rem;font-weight:700;color:#fff;letter-spacing:.05em;font-family:'IBM Plex Mono',monospace;flex-shrink:0;">
                            {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}{{ mb_strtoupper(mb_substr(explode(' ', auth()->user()->name)[1] ?? '', 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-size:.9375rem;font-weight:700;color:#0f172a;">
                                {{ auth()->user()->name }}
                            </div>
                            <div style="font-size:.75rem;color:#64748b;margin-top:2px;">
                                {{ auth()->user()->email }}
                            </div>
                        </div>
                    </div>

                    <div style="border-top:1px solid #f1f5f9;padding:14px 20px;">
                        <div style="display:flex;flex-direction:column;gap:10px;">
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span style="font-size:.6875rem;color:#94a3b8;font-weight:600;letter-spacing:.04em;text-transform:uppercase;">Bergabung</span>
                                <span style="font-size:.75rem;color:#475569;font-weight:500;">
                                    {{ auth()->user()->created_at->translatedFormat('d M Y') }}
                                </span>
                            </div>
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span style="font-size:.6875rem;color:#94a3b8;font-weight:600;letter-spacing:.04em;text-transform:uppercase;">Status</span>
                                <span style="font-size:.6875rem;font-weight:600;color:#16a34a;background:#dcfce7;padding:2px 8px;border-radius:20px;">Aktif</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Menu navigasi cepat --}}
                <div class="profile-card" style="padding:8px;">
                    <a href="#section-profile" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;text-decoration:none;color:#475569;font-size:.8125rem;font-weight:500;transition:background .15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <svg width="16" height="16" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Informasi Profil
                    </a>
                    <a href="#section-password" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;text-decoration:none;color:#475569;font-size:.8125rem;font-weight:500;transition:background .15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <svg width="16" height="16" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Ubah Password
                    </a>
                    <a href="#section-delete" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;text-decoration:none;color:#475569;font-size:.8125rem;font-weight:500;transition:background .15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <svg width="16" height="16" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                        Hapus Akun
                    </a>
                </div>
            </div>

            {{-- ── Main: Form Sections ───────────────────────────────── --}}
            <div style="display:flex;flex-direction:column;gap:20px;">

                {{-- Informasi Profil --}}
                <div class="profile-card" id="section-profile">
                    <div class="profile-card-header">
                        <div class="section-icon" style="background:#eff6ff;">
                            <svg width="16" height="16" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <div>
                            <h3>Informasi Profil</h3>
                            <p>Perbarui nama dan alamat email akun Anda</p>
                        </div>
                    </div>
                    <div class="profile-card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- Ubah Password --}}
                <div class="profile-card" id="section-password">
                    <div class="profile-card-header">
                        <div class="section-icon" style="background:#f0fdf4;">
                            <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </div>
                        <div>
                            <h3>Ubah Password</h3>
                            <p>Gunakan password yang panjang dan acak untuk keamanan akun</p>
                        </div>
                    </div>
                    <div class="profile-card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                {{-- Hapus Akun --}}
                <div class="profile-card" id="section-delete" style="border-color:#fee2e2;">
                    <div class="profile-card-header" style="background:#fff8f8;">
                        <div class="section-icon" style="background:#fee2e2;">
                            <svg width="16" height="16" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                        </div>
                        <div>
                            <h3 style="color:#dc2626;">Hapus Akun</h3>
                            <p>Tindakan ini tidak dapat dibatalkan</p>
                        </div>
                    </div>
                    <div class="profile-card-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
