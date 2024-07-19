
@props(['text', 'route','currentRoute'])
@vite(['resources/css/app.css', 'resources/js/app.js'])
<a href="{{ route($route) }}" class="{{ $currentRoute == $route ? 'text-sky-400' : '' }}">
    {{ $text }}
</a>