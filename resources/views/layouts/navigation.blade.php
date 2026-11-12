<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 text-slate-900 shadow-sm backdrop-blur transition-colors dark:border-slate-800 dark:bg-slate-950/90 dark:text-white">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" aria-label="My Task Board">
                        <x-application-logo-clean class="block h-9 w-auto text-indigo-600 dark:text-indigo-400" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden items-center gap-4 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('todos.index')" :active="request()->routeIs('todos.index')">
                        {{ __('Todo List') }}
                    </x-nav-link>
                    <x-nav-link :href="route('todos.calendar')" :active="request()->routeIs('todos.calendar')">
                        {{ __('Calendar') }}
                    </x-nav-link>
                    <x-nav-link :href="route('todos.planner')" :active="request()->routeIs('todos.planner')">
                        {{ __('Planner') }}
                    </x-nav-link>
                    <x-nav-link :href="route('daily-notes.index')" :active="request()->routeIs('daily-notes.*')">
                        {{ __('Daily Notes') }}
                    </x-nav-link>
                    <x-nav-link :href="route('todos.trash')" :active="request()->routeIs('todos.trash')">
                        {{ __('Trash') }}
                    </x-nav-link>
                    <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">
                        {{ __('Profile') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- User actions -->
            <div class="hidden items-center gap-3 sm:flex sm:ms-6">
                <span class="text-sm font-semibold text-slate-600 dark:text-slate-300">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-3.5 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 dark:focus:ring-offset-slate-950">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3-3H9m0 0l3-3m-3 3l3 3" /></svg>
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button type="button" @click.stop="open = ! open" :aria-expanded="open.toString()" aria-label="Buka menu navigasi" class="relative z-50 inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white p-2.5 text-slate-600 shadow-sm hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div x-cloak x-show="open" x-transition class="sm:hidden border-t border-slate-200 bg-white/95 shadow-lg dark:border-slate-800 dark:bg-slate-950/95">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('todos.index')" :active="request()->routeIs('todos.index')">
                {{ __('Todo List') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('todos.calendar')" :active="request()->routeIs('todos.calendar')">
                {{ __('Calendar') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('todos.planner')" :active="request()->routeIs('todos.planner')">
                {{ __('Planner') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('daily-notes.index')" :active="request()->routeIs('daily-notes.*')">
                {{ __('Catatan Daily') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('todos.trash')" :active="request()->routeIs('todos.trash')">
                {{ __('Trash') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">
                {{ __('Profile') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
