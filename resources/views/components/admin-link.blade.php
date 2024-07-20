
@props(['text', 'route','currentRoute'])
@vite(['resources/css/app.css', 'resources/js/app.js'])
<a href="{{ route($route) }}"
 class="{{ $currentRoute == $route ? 
 'relative flex  w-40 items-center justify-center overflow-hidden bg-blue-600 font-medium text-white shadow-2xl transition-all duration-300 before:absolute before:inset-0 before:border-0 before:border-white before:duration-100 before:ease-linear h hover:text-blue-600 hover:shadow-blue-600 hover:before:border-[25px]'
 : 
 'relative flex w-40 items-center justify-center overflow-hidden  font-medium  shadow-2xl transition-all duration-300 before:absolute before:inset-0 before:border-0 before:border-blue-600 before:duration-100 before:ease-linear  hover:bg-blue-600  hover:text-white hover:shadow-blue-600 hover:before:border-[25px]' }}">
    
    <span class="relative z-10">{{ $text }}</span>
</a>


