<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel CRUD') }}</title>

    <!-- Vite for Tailwind CSS and JavaScript -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
<!-- Navbar -->
<nav class="bg-blue-600 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <a href="/" class="text-white font-bold text-xl">Travel Insurance Quote</a>
            </div>
        </div>
    </div>
</nav>

<!-- Main Content Area -->
<main class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            @yield('content')
        </div>
    </div>
</main>

<!-- Livewire Scripts -->
@livewireScripts
</body>
</html>




