<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=playfair-display:700,900&display=swap" rel="stylesheet" />


        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-black relative">
            
            <img src="https://images.pexels.com/photos/2061820/pexels-photo-2061820.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2"
                 alt="Suasana Barbershop Mancave"
                 class="absolute inset-0 w-full h-full object-cover opacity-40">

            <div class="relative z-10 w-full flex flex-col justify-center items-center">
                <div>
                    <a href="/" class="flex justify-center">
                        <h1 class="font-black text-4xl text-white drop-shadow-lg" style="font-family: 'Playfair Display', serif;">
                            MANCAVE
                        </h1>
                    </a>
                </div>

                <div class="w-full sm:max-w-lg mt-6 px-6 py-8 bg-transparent overflow-hidden sm:rounded-lg">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>