<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex items-center justify-center bg-slate-100 transition-colors dark:bg-gray-950 px-4 py-10">
            <div class="w-full max-w-5xl overflow-hidden rounded-3xl bg-white shadow-2xl shadow-slate-300/50 dark:bg-gray-900 lg:grid lg:grid-cols-2">
                    <div class="hidden lg:flex flex-col justify-between bg-slate-800 dark:bg-gray-800 p-12 text-white">
                    <div>
                        <div class="mb-8 flex h-14 w-14 items-center justify-center rounded-2xl bg-white/15 backdrop-blur" aria-hidden="true"><svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H5v4m0 0 4-4m-4 4a7 7 0 1 0 2-5"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 19h4v-4m0 0-4 4m4-4a7 7 0 1 0-2 5"/></svg></div>
                        <h1 class="text-4xl font-black leading-tight">Rencanakan hari.<br> Selesaikan tugas.</h1>
                        <p class="mt-5 max-w-sm text-slate-300">Kelola semua pekerjaan dan catatan Anda dalam satu task board yang sederhana.</p>
                    </div>
                    <p class="text-sm text-slate-400">My Task Board · Fokus pada hal penting</p>
                </div>

                <div class="p-6 sm:p-10 lg:p-12">
                    <a href="/" class="mb-8 flex items-center gap-3 lg:hidden">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-white" aria-hidden="true"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H5v4m0 0 4-4m-4 4a7 7 0 1 0 2-5"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 19h4v-4m0 0-4 4m4-4a7 7 0 1 0-2 5"/></svg></span>
                        <span class="text-xl font-black text-gray-900">My Task Board</span>
                    </a>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
