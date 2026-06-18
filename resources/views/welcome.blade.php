@php
    $siteName = config('app.name', 'viasco prjkt.');
    $metaTitle = $metaTitle ?? 'viasco prjkt. — Laravel dev, visual storyteller';
    $metaDescription = $metaDescription ?? 'Membangun web yang enak dilihat, terasa hidup, dan selesai tepat waktu. Portfolio Bimo Alfarrabi.';
    $metaImageIsAbsolute = $metaImageIsAbsolute ?? false;
    $metaImage = $metaImage ?? 'og-image.png';
    $metaImage = $metaImageIsAbsolute ? $metaImage : asset($metaImage);
    $metaUrl = url()->current();
    $metaLocale = str_replace('-', '_', str_replace('_', '-', app()->getLocale()));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $metaTitle }}</title>
        <meta name="description" content="{{ $metaDescription }}">
        <link rel="canonical" href="{{ $metaUrl }}">
        <link rel="icon" type="image/svg+xml" href="/favicon.svg">
        <meta name="timezone" content="{{ config('app.timezone') }}">
        <meta name="theme-color" content="#F5F5F3">

        {{-- Open Graph --}}
        <meta property="og:site_name" content="{{ $siteName }}">
        <meta property="og:type" content="website">
        <meta property="og:title" content="{{ $metaTitle }}">
        <meta property="og:description" content="{{ $metaDescription }}">
        <meta property="og:url" content="{{ $metaUrl }}">
        <meta property="og:image" content="{{ $metaImage }}">
        <meta property="og:image:secure_url" content="{{ $metaImage }}">
        <meta property="og:image:type" content="image/png">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:image:alt" content="{{ $metaTitle }}">
        <meta property="og:locale" content="{{ $metaLocale }}">

        {{-- Twitter --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $metaTitle }}">
        <meta name="twitter:description" content="{{ $metaDescription }}">
        <meta name="twitter:image" content="{{ $metaImage }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|jetbrains-mono:400,500,600" rel="stylesheet" />
        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    </head>
    <body class="bg-surface-0 font-sans text-ink antialiased">
        <div id="app"></div>
        <script>
            window.__PORTFOLIO_DATA__ = @json($portfolioData ?? []);
        </script>
    </body>
</html>
