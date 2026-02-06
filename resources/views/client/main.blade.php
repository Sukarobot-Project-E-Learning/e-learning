<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sukarobot Academy</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="{{  asset('assets/elearning/client/css/partials/navbar.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Meta Tags -->
    @stack('meta')

    <!-- CSS Khusus Halaman -->
      @yield('css')
    <script></script>
</head>

<body>
    @include('client.partials.navbar')
    @yield('body')
    @include('client.partials.footer')

<!-- Script Khusus Halaman -->
@yield('js')

<script src="{{ asset('assets/elearning/client/js/partials/navbar.js') }}"></script>

</body>

</html>
