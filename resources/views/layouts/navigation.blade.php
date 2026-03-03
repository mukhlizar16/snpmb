<style>
    [x-cloak] { display: none !important; }

    .snpmb-nav {
        background: #0b1623;
        border-bottom: 1px solid #18293e;
        position: sticky; top: 0; z-index: 50;
        font-family: 'IBM Plex Sans', 'Figtree', sans-serif;
    }
    .snpmb-nav-inner {
        max-width: 80rem; margin: 0 auto;
        padding: 0 1.5rem;
        display: flex; justify-content: space-between; align-items: center;
        height: 56px;
    }
    .snpmb-brand {
        display: flex; align-items: center; gap: 28px;
    }
    .snpmb-logo-link {
        display: flex; align-items: center; gap: 10px; text-decoration: none;
    }
    .snpmb-logo-icon {
        width: 32px; height: 32px;
        background: linear-gradient(135deg, #1d4ed8, #3b82f6);
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(59,130,246,.35);
    }
    .snpmb-wordmark { line-height: 1; }
    .snpmb-wordmark-title {
        font-family: 'IBM Plex Mono', monospace;
        font-size: .8rem; font-weight: 700;
        color: #f1f5f9; letter-spacing: .06em;
        display: block;
    }
    .snpmb-wordmark-sub {
        font-family: 'IBM Plex Mono', monospace;
        font-size: .55rem; color: #2d4a66;
        letter-spacing: .1em; text-transform: uppercase;
        display: block; margin-top: 2px;
    }

    /* Nav links */
    .snpmb-nav-links {
        display: none;
        align-items: center;
        gap: 2px;
    }
    .snpmb-nav-link {
        display: flex; align-items: center; gap: 6px;
        font-size: .8125rem; font-weight: 500;
        padding: 6px 12px; border-radius: 8px;
        text-decoration: none;
        transition: background .15s, color .15s;
        color: #4a6585;
    }
    .snpmb-nav-link:hover { color: #cbd5e1; background: #18293e; }
    .snpmb-nav-link.active { color: #f1f5f9; background: #18293e; }
    .snpmb-nav-link.active svg { color: #3b82f6; }

    /* User button */
    .snpmb-user-btn {
        display: flex; align-items: center; gap: 8px;
        background: transparent;
        border: 1px solid #18293e;
        border-radius: 10px;
        padding: 5px 10px 5px 6px;
        cursor: pointer;
        transition: background .15s, border-color .15s;
        color: #94a3b8;
    }
    .snpmb-user-btn:hover { background: #18293e; border-color: #27405c; }
    .snpmb-user-avatar {
        width: 26px; height: 26px;
        background: linear-gradient(135deg, #1d4ed8, #3b82f6);
        border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        font-family: 'IBM Plex Mono', monospace;
        font-size: .65rem; font-weight: 700; color: #fff;
    }
    .snpmb-user-name {
        font-size: .8125rem; font-weight: 500; color: #94a3b8;
        max-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }

    /* Dropdown */
    .snpmb-dropdown {
        position: absolute; right: 0; top: calc(100% + 8px);
        width: 210px;
        background: #0b1623;
        border: 1px solid #18293e;
        border-radius: 14px;
        padding: 6px;
        box-shadow: 0 12px 40px rgba(0,0,0,.5);
    }
    .snpmb-dropdown-header {
        padding: 8px 10px 10px;
        border-bottom: 1px solid #18293e;
        margin-bottom: 4px;
    }
    .snpmb-dropdown-name { font-size: .8rem; font-weight: 600; color: #f1f5f9; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .snpmb-dropdown-email { font-size: .67rem; color: #334155; margin-top: 1px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .snpmb-dropdown-item {
        display: flex; align-items: center; gap: 8px;
        padding: 7px 10px; border-radius: 8px;
        font-size: .8rem; color: #64748b;
        text-decoration: none;
        transition: background .12s, color .12s;
        cursor: pointer; width: 100%; background: none; border: none; text-align: left;
    }
    .snpmb-dropdown-item:hover { background: #18293e; color: #cbd5e1; }
    .snpmb-dropdown-item.danger:hover { background: #2d1a1a; color: #fca5a5; }

    /* Hamburger */
    .snpmb-hamburger {
        display: flex;
        background: none; border: 1px solid #18293e;
        border-radius: 8px; padding: 6px;
        cursor: pointer; color: #4a6585;
        transition: background .15s, color .15s;
    }
    .snpmb-hamburger:hover { background: #18293e; color: #cbd5e1; }

    /* Mobile menu */
    .snpmb-mobile-menu {
        background: #0b1623;
        border-top: 1px solid #18293e;
        padding: 8px 1rem 12px;
    }
    .snpmb-mobile-link {
        display: flex; align-items: center; gap: 8px;
        padding: 9px 12px; border-radius: 8px;
        font-size: .875rem; color: #4a6585;
        text-decoration: none;
        transition: background .12s, color .12s;
    }
    .snpmb-mobile-link:hover { background: #18293e; color: #cbd5e1; }
    .snpmb-mobile-link.active { background: #18293e; color: #f1f5f9; }
    .snpmb-mobile-divider {
        height: 1px; background: #18293e;
        margin: 8px 0;
    }
    .snpmb-mobile-user {
        padding: 8px 12px 4px;
        font-size: .75rem;
    }

    @media (min-width: 640px) {
        .snpmb-nav-links { display: flex; }
        .snpmb-hamburger { display: none; }
    }
</style>

<nav x-data="{ open: false }" class="snpmb-nav">
    <div class="snpmb-nav-inner">

        {{-- ── Brand + Desktop links ─────────────────────── --}}
        <div class="snpmb-brand">

            <a href="{{ route('dashboard') }}" class="snpmb-logo-link">
                <div class="snpmb-logo-icon">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="white">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                    </svg>
                </div>
                <div class="snpmb-wordmark">
                    <span class="snpmb-wordmark-title">SNPMB</span>
                    <span class="snpmb-wordmark-sub">Data System</span>
                </div>
            </a>

            <nav class="snpmb-nav-links">
                <a href="{{ route('dashboard') }}"
                   class="snpmb-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('import.index') }}"
                   class="snpmb-nav-link {{ request()->routeIs('import.*') ? 'active' : '' }}">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Import Data
                </a>
                <a href="{{ route('data.siswa') }}"
                   class="snpmb-nav-link {{ request()->routeIs('data.*') ? 'active' : '' }}">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 10h18M3 14h18M10 6h4M10 18h4M5 6a2 2 0 00-2 2v8a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2H5z"/>
                    </svg>
                    Data Siswa
                </a>
            </nav>
        </div>

        {{-- ── User dropdown ─────────────────────────────── --}}
        <div style="display:flex;align-items:center;gap:10px;">

            <div style="position:relative;" x-data="{ userOpen: false }">
                <button @click="userOpen = !userOpen" @click.outside="userOpen = false"
                        class="snpmb-user-btn">
                    <div class="snpmb-user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="snpmb-user-name">{{ Auth::user()->name }}</span>
                    <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         style="flex-shrink:0;transition:transform .15s;color:#334155;"
                         :style="userOpen ? 'transform:rotate(180deg)' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="userOpen" x-cloak class="snpmb-dropdown"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-1">

                    <div class="snpmb-dropdown-header">
                        <div class="snpmb-dropdown-name">{{ Auth::user()->name }}</div>
                        <div class="snpmb-dropdown-email">{{ Auth::user()->email }}</div>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="snpmb-dropdown-item">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Profil Saya
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="snpmb-dropdown-item danger">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

            {{-- Hamburger (mobile) --}}
            <button @click="open = !open" class="snpmb-hamburger">
                <svg width="18" height="18" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                          stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                          stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- ── Mobile menu ──────────────────────────────────────── --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden snpmb-mobile-menu">
        <a href="{{ route('dashboard') }}"
           class="snpmb-mobile-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>
        <a href="{{ route('import.index') }}"
           class="snpmb-mobile-link {{ request()->routeIs('import.*') ? 'active' : '' }}">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Import Data
        </a>
        <a href="{{ route('data.siswa') }}"
           class="snpmb-mobile-link {{ request()->routeIs('data.*') ? 'active' : '' }}">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 10h18M3 14h18M10 6h4M10 18h4M5 6a2 2 0 00-2 2v8a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2H5z"/>
            </svg>
            Data Siswa
        </a>

        <div class="snpmb-mobile-divider"></div>

        <div class="snpmb-mobile-user">
            <div style="font-size:.8rem;font-weight:600;color:#f1f5f9;">{{ Auth::user()->name }}</div>
            <div style="font-size:.7rem;color:#334155;margin-top:1px;">{{ Auth::user()->email }}</div>
        </div>

        <a href="{{ route('profile.edit') }}" class="snpmb-mobile-link">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profil Saya
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="snpmb-mobile-link" style="width:100%;background:none;border:none;cursor:pointer;">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</nav>
