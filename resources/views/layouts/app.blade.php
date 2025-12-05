<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <!-- WAJIB agar CSRF tidak error -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LearnFlux LMS - @yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900 antialiased">

    <!-- WAJIB untuk menjaga session csrf -->
    <script>
        window.Laravel = { csrfToken: "{{ csrf_token() }}" };
    </script>

    <main class="min-h-screen">
        @yield('content')
    </main>

</body>
</html>
