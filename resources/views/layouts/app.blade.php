<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Task Management')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800">

    <div class="min-h-screen">

        {{-- Tap outside the sidebar (mobile) closes it --}}
        <div id="sidebar-overlay" class="fixed inset-0 z-40 hidden bg-black/40 lg:hidden"></div>

        @include('components.sidebar')

        <main class="lg:ml-64">
            @include('components.header')

            <div class="p-4 sm:p-6 lg:p-8">
                @yield('content')
            </div>
        </main>

    </div>

    @stack('scripts')

</body>
</html>
