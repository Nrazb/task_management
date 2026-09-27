<header class="sticky top-0 z-30 border-b border-slate-200 bg-white">

    <div class="flex h-20 items-center justify-between px-4 sm:px-6 lg:px-8">

        {{-- Left --}}
        <div class="flex items-center gap-4">

            <button
                id="sidebar-toggle"
                type="button"
                aria-label="Open menu"
                class="rounded-lg p-2.5 text-slate-600 hover:bg-slate-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 lg:hidden"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div>
                <h2 class="text-lg font-bold text-slate-900">Dashboard</h2>
                <p class="hidden text-sm text-slate-500 sm:block">Manage your tasks efficiently</p>
            </div>

        </div>

        {{-- Right --}}
        <div class="flex items-center gap-3">

            <div class="relative">
                <button
                    id="notif-toggle"
                    type="button"
                    aria-haspopup="true"
                    aria-label="Notifications"
                    class="rounded-full p-2.5 text-slate-500 hover:bg-slate-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </button>

                <div
                    id="notif-panel"
                    class="absolute right-0 z-40 mt-2 hidden w-72 rounded-xl border border-slate-200 bg-white p-4 shadow-lg"
                >
                    <p class="text-sm font-semibold text-slate-800">Notifications</p>
                    <p class="mt-2 text-sm text-slate-500">No new notifications.</p>
                </div>
            </div>

            {{-- Profile --}}
            <div class="hidden items-center gap-3 sm:flex">
                <div class="h-9 w-9 overflow-hidden rounded-full bg-blue-100">
                    <div class="flex h-full w-full items-center justify-center font-semibold text-blue-600">
                        {{ auth()->user() ? strtoupper(substr(auth()->user()->name, 0, 1)) : '?' }}
                    </div>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name ?? 'Guest' }}</p>
                    <p class="text-xs text-slate-500">{{ auth()->user()->email ?? '' }}</p>
                </div>
            </div>

        </div>

    </div>

</header>
