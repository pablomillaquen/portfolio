<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $seo['title'] ?? 'Pablo Millaquen — Desarrollador & Investigador' }}</title>
    <meta name="description" content="{{ $seo['description'] ?? 'Portfolio profesional de Pablo Millaquen. Desarrollador de software e investigador especializado en logística, IA y arquitectura de software.' }}">
    <meta property="og:title" content="{{ $seo['title'] ?? 'Pablo Millaquen — Desarrollador & Investigador' }}">
    <meta property="og:description" content="{{ $seo['description'] ?? 'Portfolio profesional de Pablo Millaquen. Desarrollador de software e investigador especializado en logística, IA y arquitectura de software.' }}">
    <meta property="og:image" content="{{ $seo['image'] ?? asset('img/og_image.png') }}">
    <meta property="og:url" content="{{ $seo['url'] ?? url('/') }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Pablo Millaquen">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo['title'] ?? 'Pablo Millaquen — Desarrollador & Investigador' }}">
    <meta name="twitter:description" content="{{ $seo['description'] ?? 'Portfolio profesional de Pablo Millaquen.' }}">
    <meta name="twitter:image" content="{{ $seo['image'] ?? asset('img/og_image.png') }}">
    <link rel="canonical" href="{{ $seo['url'] ?? url('/') }}">
    <link rel="alternate" hreflang="es" href="{{ url('/') }}?locale=es">
    <link rel="alternate" hreflang="en" href="{{ url('/') }}?locale=en">
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Person",
        "name": "Pablo Millaquen",
        "jobTitle": "Desarrollador & Investigador",
        "url": "https://pablomillaquen.com",
        "sameAs": []
    }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app"></div>
</body>
</html>
