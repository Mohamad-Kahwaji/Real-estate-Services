@php $isRtl = app()->getLocale() === 'ar'; @endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Auth')</title>

    @vite(['resources/assets/vendor/fonts/iconify/iconify.css'])
    @vite(['resources/css/app.css'])

    @if($isRtl)
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    @endif

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
