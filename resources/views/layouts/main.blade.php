<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title class="print:hidden">{{ config('app.name', 'Gotas de Mel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">



    <style>
    @media print {
        body * {
            @apply hidden;
            width: 100%;
            height: 100%
        }

        main {
            width: 100%;
            height: 100%
        }

        .print-hidden {

            display: none;
        }

        .printable,
        .printable * {
            @apply block;
            visibility: visible;
        }

        .printable {
            position: absolute;
            left: 50%;

        }

        @page {
            margin: 0;
        }

        body {
            margin: 0;
        }
    }

    .fade-in {
        opacity: 1;
        transition: opacity 0.5s ease-in-out;
    }

    .fade-out {
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
    }
    </style>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">


        <!-- Page Heading -->
        @livewire('nav-bar')
        @livewireScripts
        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>


</body>

</html>