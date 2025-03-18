<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ Vite::asset('resources/img/favicon.png') }}" type="image/x-icon">
    <title>@yield('title', 'BookStyle')</title>
    @vite('resources/css/register.css')
</head>

<body>
    @yield('content')
    @vite('resources/js/register.js')
</body>

</html>