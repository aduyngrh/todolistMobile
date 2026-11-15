<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <style>[x-cloak] { display: none !important; }</style>
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
        <div class="min-h-screen bg-slate-50 text-slate-900 transition-colors dark:bg-slate-950 dark:text-slate-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="border-b border-slate-200/80 bg-white/90 text-slate-900 shadow-sm backdrop-blur transition-colors dark:border-slate-800 dark:bg-slate-900/90 dark:text-white">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Toast Notification System -->
            <div id="toast-container" class="fixed right-5 top-5 z-50 flex flex-col gap-2.5 pointer-events-none">
                @if (session('success'))
                    <div id="flash-message" class="pointer-events-auto flex items-center gap-3 rounded-2xl border border-emerald-300 dark:border-emerald-700/80 bg-white/95 dark:bg-[#181f2c]/95 px-5 py-3.5 text-sm font-bold text-slate-800 dark:text-white shadow-2xl backdrop-blur-md transition-all duration-300" role="status">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-950/80 dark:text-emerald-400 text-base font-black" aria-hidden="true">✓</span>
                        <span>{{ session('success') }}</span>
                        <button type="button" onclick="document.getElementById('flash-message')?.remove()" class="ml-2 inline-flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 text-lg font-bold" aria-label="Tutup">×</button>
                    </div>
                    <script>
                        setTimeout(() => {
                            const el = document.getElementById('flash-message');
                            if (el) {
                                el.style.opacity = '0';
                                el.style.transform = 'translateY(-8px)';
                                setTimeout(() => el.remove(), 300);
                            }
                        }, 4000);
                    </script>
                @endif
            </div>

            <script>
                window.showToast = function (message, type = 'success') {
                    const container = document.getElementById('toast-container');
                    if (!container) return;
                    const toast = document.createElement('div');
                    const isError = type === 'error';
                    const borderColor = isError ? 'border-rose-300 dark:border-rose-700/80' : 'border-emerald-300 dark:border-emerald-700/80';
                    const iconBg = isError ? 'bg-rose-100 text-rose-600 dark:bg-rose-950/80 dark:text-rose-400' : 'bg-emerald-100 text-emerald-600 dark:bg-emerald-950/80 dark:text-emerald-400';
                    const icon = isError ? '✕' : '✓';
                    toast.className = `pointer-events-auto flex items-center gap-3 rounded-2xl border ${borderColor} bg-white/95 dark:bg-[#181f2c]/95 px-5 py-3.5 text-sm font-bold text-slate-800 dark:text-white shadow-2xl backdrop-blur-md transition-all duration-300 transform translate-y-0`;
                    toast.innerHTML = `
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl ${iconBg} text-base font-black" aria-hidden="true">${icon}</span>
                        <span>${message}</span>
                        <button type="button" onclick="this.parentElement.remove()" class="ml-2 inline-flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 text-lg font-bold" aria-label="Tutup">×</button>
                    `;
                    container.appendChild(toast);
                    setTimeout(() => {
                        toast.style.opacity = '0';
                        toast.style.transform = 'translateY(-8px)';
                        setTimeout(() => toast.remove(), 300);
                    }, 4000);
                };
            </script>
            <main>
                {{ $slot }}
            </main>
            <button type="button" onclick="toggleTheme()" class="fixed bottom-5 right-5 z-40 inline-flex h-12 w-12 items-center justify-center rounded-full bg-gray-900 text-white shadow-lg transition hover:scale-105 dark:bg-white dark:text-gray-900" aria-label="Ganti tema">
                <svg class="h-5 w-5 dark:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2M4.22 4.22l1.42 1.42m12.72 12.72 1.42 1.42M3 12h2m14 0h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42M12 17a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z"/></svg>
                <svg class="hidden h-5 w-5 dark:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.8A8.5 8.5 0 1 1 11.2 3 6.5 6.5 0 0 0 21 12.8Z"/></svg>
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
