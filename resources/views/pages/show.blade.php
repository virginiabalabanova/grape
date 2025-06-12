<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page->name ?? config('app.name', 'Laravel') }}</title>

    {{-- Include base CSS (Tailwind) via Vite --}}
    @vite(['resources/css/app.css'])

    {{-- Inject the CSS rendered from GrapesJS data (component-specific styles) --}}
    @if(!empty($renderedCss))
        <style type="text/css">
            {!! $renderedCss !!}
        </style>
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/dynamic-colors.css" />
    <link rel="stylesheet" href="/dynamic-theme.css" />
</head>
<body class="font-sans antialiased">
    {{-- Output the HTML rendered from GrapesJS data --}}
    @if(!empty($renderedHtml))
        {!! $renderedHtml !!}
    @else
        <p class="text-gray-500">No content available.</p> {{-- Fallback --}}
    @endif

    {{-- Include base JS if needed --}}
    {{-- Example: <script src="{{ asset('build/assets/app-*.js') }}" defer></script> --}}
    {{-- Or use Vite directive: @vite(['resources/js/app.js']) --}}

</body>
</html>
