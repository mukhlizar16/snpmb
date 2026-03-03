<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SNPMB') }} — Sistem Manajemen Data Nasional</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=ibm-plex-sans:400,500,600,700,800|ibm-plex-mono:400,500,600,700&display=swap"
              rel="stylesheet"/>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="font-sans antialiased" style="background:#f1f5f9;min-height:100vh;">

        @include('layouts.navigation')

        {{-- Page-level header slot (opsional — halaman bisa skip ini) --}}
        @isset($header)
            <header style="background:#fff;border-bottom:1px solid #e2e8f0;">
                <div style="max-width:80rem;margin:0 auto;padding:18px 1.5rem;display:flex;align-items:center;gap:12px;">
                    <div style="width:3px;height:20px;background:#2563eb;border-radius:4px;flex-shrink:0;"></div>
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main>
            {{ $slot }}
        </main>

        @stack('scripts')
    </body>
</html>
