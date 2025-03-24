<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ Vite::asset('resources/img/favicon.png') }}" type="image/x-icon">
    <title>@yield('title', 'BookStyle')</title>
    @vite('resources/css/bookRegister.css')
</head>

<body>
    @yield('content')
    @vite('resources/js/bookRegister.js')
</body>

</html>