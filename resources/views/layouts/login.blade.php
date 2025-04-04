<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ Vite::asset('resources/img/favicon.png') }}" type="image/x-icon">
    <title>@yield('title', 'BookStyle')</title>
    @vite('resources/css/login.css')
    @vite('resources/css/register.css')
</head>

<body>
    @yield('content')
    @vite('resources/js/login.js')
    @vite('resources/js/register.js')
</body>

</html>