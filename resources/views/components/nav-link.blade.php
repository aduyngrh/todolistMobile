@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1.5 sm:px-2 lg:px-3 pt-1 border-b-2 border-indigo-600 dark:border-indigo-400 text-xs sm:text-sm font-semibold leading-5 text-slate-900 dark:text-white focus:outline-none transition duration-150 ease-in-out whitespace-nowrap'
            : 'inline-flex items-center px-1.5 sm:px-2 lg:px-3 pt-1 border-b-2 border-transparent text-xs sm:text-sm font-medium leading-5 text-slate-500 hover:text-slate-800 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:border-slate-700 focus:outline-none transition duration-150 ease-in-out whitespace-nowrap';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
