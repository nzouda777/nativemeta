<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'NativeMeta') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Clash Display Font (Assuming local or CDN for premium look) -->
        <link href="https://api.fontshare.com/v2/css?f[]=clash-display@700,600,500&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @routes
        @viteReactRefresh
        @vite(['resources/js/app.jsx', "resources/js/Pages/{$page['component']}.jsx"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased text-white bg-[#0A0A0B]">
        @inertia
        
        <!-- Custom Cursor elements -->
        <div id="custom-cursor"></div>
        <div id="custom-cursor-follower"></div>
    </body>
</html>
