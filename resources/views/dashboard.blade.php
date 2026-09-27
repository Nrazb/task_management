@extends('layouts.app')

@section('title', 'Dashboard | TaskFlow')

@section('content')

    {{-- Welcome --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
            Welcome back, {{ auth()->user()->name ?? 'there' }}
        </h1>
        <p class="mt-1 text-sm text-slate-500">Here's what's happening with your tasks today.</p>
    </div>

    {{-- Statistics --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Tasks</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $statistics['total'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5V3m0 2h6" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Pending</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $statistics['pending'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">In Progress</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $statistics['in_progress'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Completed</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $statistics['completed'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
        </div>

    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

        {{-- Tasks --}}
        <div class="xl:col-span-2">

            {{-- FIX: kartu ini sekarang membungkus header DAN #task-container,
                 jadi loading/error/empty/table/pagination tampil menyatu di
                 dalam satu card putih yang sama, bukan sebagai elemen lepas
                 di luar card setelah ditutup lebih awal. --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 p-5">
                    {{-- Was nested inside an identical copy of itself before, which
                         doubled the flex-row-at-sm rule for no reason. Title+button
                         now group on their own so they can wrap independently of the
                         search box next to them. --}}
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">Tasks</h2>
                                <p class="text-sm text-slate-500">Manage your recent tasks</p>
                            </div>

                            <button
                                id="open-create-task"
                                type="button"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-400 focus-visible:ring-offset-2"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                New Task
                            </button>
                        </div>

                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z" />
                            </svg>
                            <input
                                id="task-search"
                                type="text"
                                placeholder="Search tasks..."
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:w-64"
                            >
                        </div>
                    </div>
                </div>

                {{-- Task Content --}}
                <div id="task-container">

                    <div id="task-loading" class="hidden px-6 py-12 text-center">
                        <div class="mx-auto h-8 w-8 animate-spin rounded-full border-4 border-slate-200 border-t-blue-600"></div>
                        <p class="mt-3 text-sm text-slate-500">Loading tasks...</p>
                    </div>

                    <div id="task-error" class="hidden px-6 py-12 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 font-semibold text-slate-800">Something went wrong</h3>
                        <p class="mt-1 text-sm text-slate-500">Failed to load tasks. Please try again.</p>
                        <button
                            id="retry-button"
                            type="button"
                            class="mt-4 rounded-xl bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-400 focus-visible:ring-offset-2"
                        >
                            Try Again
                        </button>
                    </div>

                    <div id="task-empty" class="hidden px-6 py-12 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h3.586a1 1 0 01.707.293l1.414 1.414a1 1 0 00.707.293H19a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 font-semibold text-slate-800">No tasks found</h3>
                        <p class="mt-1 text-sm text-slate-500">There are no tasks matching your search.</p>
                    </div>

                    {{-- "hidden ... md:block" on one element is a Tailwind trap: at md+
                         widths md:block always wins over hidden, so JS adding "hidden"
                         back (to show the error/loading state) never actually hid this
                         table on desktop. Splitting the JS-controlled state from the
                         desktop-only display fixes it: the outer div is the only one
                         the script touches, and a display:none parent hides everything
                         inside regardless of the inner element's own responsive class. --}}
                    <div id="task-table-wrapper" class="hidden">
                      <div class="overflow-x-auto">
                        <table class="hidden w-full md:table">
                            <thead class="bg-slate-50">
                                <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    <th class="px-6 py-4">Task</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Priority</th>
                                    <th class="px-6 py-4">Created</th>
                                </tr>
                            </thead>
                            <tbody id="task-table-body" class="divide-y divide-slate-100"></tbody>
                        </table>
                      </div>
                    </div>

                    <div id="task-mobile-wrapper" class="hidden divide-y divide-slate-100 md:hidden"></div>

                    <div id="task-pagination" class="hidden border-t border-slate-200 px-5 py-4"></div>

                </div>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div>
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 p-5">
                    <h2 class="font-semibold text-slate-900">Recent Activity</h2>
                    <p class="mt-1 text-sm text-slate-500">Latest task updates</p>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse ($recentActivities as $activity)
                        <div class="flex gap-3 p-5">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-slate-800">{{ $activity->title }}</p>
                                <p class="mt-1 text-xs text-slate-500">
                                    Task status: {{ ucfirst(str_replace('_', ' ', $activity->status)) }}
                                </p>
                                <p class="mt-1 text-xs text-slate-400">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-sm text-slate-500">No recent activity.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- Success Toast --}}
    <div
        id="success-toast"
        class="fixed right-5 top-5 z-60 hidden w-[calc(100%-2.5rem)] max-w-sm rounded-2xl border border-emerald-100 bg-white p-4 shadow-xl"
    >
        <div class="flex items-start gap-3">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <p class="font-semibold text-slate-800">Success</p>
                <p id="success-toast-message" class="mt-1 text-sm text-slate-500"></p>
            </div>
        </div>
    </div>

    {{-- Create Task Modal --}}
    <div
        id="create-task-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4"
    >
        <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl">

            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Create New Task</h3>
                    <p class="mt-1 text-sm text-slate-500">Add a new task to your list.</p>
                </div>

                <button
                    id="close-create-task"
                    type="button"
                    aria-label="Close"
                    class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- @csrf and the hidden user_id are what TaskController@store's
                 validation rules ('user_id' => required|exists:users,id) expect;
                 without them every submit would fail before touching the title
                 field at all. --}}
            <form id="create-task-form" class="space-y-5 px-6 py-5">
                @csrf
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Title</label>
                    <input
                        id="create-title"
                        type="text"
                        name="title"
                        required
                        maxlength="255"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        placeholder="Enter task title"
                    >
                    <p id="create-title-error" class="mt-1 hidden text-xs text-red-500"></p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Description</label>
                    <textarea
                        id="create-description"
                        name="description"
                        rows="4"
                        class="w-full resize-none rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        placeholder="Enter task description"
                    ></textarea>
                    <p id="create-description-error" class="mt-1 hidden text-xs text-red-500"></p>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Status</label>
                        <select
                            id="create-status"
                            name="status"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Priority</label>
                        <select
                            id="create-priority"
                            name="priority"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </div>

                <div id="create-task-error" class="hidden rounded-xl bg-red-50 px-4 py-3 text-sm text-red-600"></div>

                <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                    <button
                        id="cancel-create-task"
                        type="button"
                        class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"
                    >
                        Cancel
                    </button>
                    <button
                        id="submit-create-task"
                        type="submit"
                        class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-400 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
