<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Auth')</title>

    @vite(['resources/css/app.css'])

    @hasSection('page-style')
        @yield('page-style')
    @endif
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<body>
    @yield('content')

    @hasSection('page-script')
        @yield('page-script')
    @endif

</body>
</html>
