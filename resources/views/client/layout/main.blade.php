<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="{{  asset('client/css/nav.css') }}">

      <!-- CSS Khusus Halaman -->
      @yield('css')
    <script></script>
</head>

<body>
    @include('client.layout.partials.navbar')
    @yield('body')
    @include('client.layout.partials.footer')


<!-- Script Khusus Halaman -->
@yield('js')

<script src="{{ asset('client/js/nav.js') }}"></script>

</body>

</html>
