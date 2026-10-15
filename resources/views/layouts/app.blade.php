<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <script>
            document.documentElement.classList.toggle('dark', localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches));
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 text-gray-900 transition-colors dark:bg-gray-950 dark:text-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white text-gray-900 shadow transition-colors dark:bg-gray-900 dark:text-white">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            @if (session('success'))
                <div id="flash-message" class="fixed right-5 top-5 z-50 flex items-center gap-3 rounded-xl border border-emerald-200 bg-white px-4 py-3 text-sm font-semibold text-emerald-700 shadow-xl dark:border-emerald-800 dark:bg-gray-900 dark:text-emerald-300" role="status">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900">✓</span>
                    <span>{{ session('success') }}</span>
                    <button type="button" onclick="document.getElementById('flash-message').remove()" class="ml-2 text-lg leading-none text-gray-400 hover:text-gray-700 dark:hover:text-gray-200" aria-label="Tutup">×</button>
                </div>
                <script>
                    setTimeout(() => document.getElementById('flash-message')?.remove(), 3500);
                </script>
            @endif
            <main>
                {{ $slot }}
            </main>
            <button type="button" onclick="toggleTheme()" class="fixed bottom-5 right-5 z-40 rounded-full bg-gray-900 p-3 text-white shadow-lg transition hover:scale-105 dark:bg-white dark:text-gray-900" aria-label="Toggle dark mode">
                <span class="dark:hidden">🌙</span><span class="hidden dark:inline">☀️</span>
            </button>
            <script>
                function toggleTheme() {
                    const dark = document.documentElement.classList.toggle('dark');
                    localStorage.theme = dark ? 'dark' : 'light';
                }
            </script>
        </div>
    </body>
</html>
