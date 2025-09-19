<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Onesky Hair Studio barbershop - Gaya Premium Pria Modern</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&family=playfair-display:700,900&display=swap" rel="stylesheet" />

    {{-- Alpine.js --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100" x-data="editBookingModal()">
    
    <header class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    {{-- Logo --}}
                    <a href="{{ route('landing') }}" class="font-black text-2xl text-gray-800" style="font-family: 'Playfair Display', serif;">
                        Onesky Hair Studio
                    </a>
                </div>

                {{-- Navigasi Tengah --}}
                <div class="hidden sm:flex sm:items-center sm:space-x-8">
                    <a href="/#services" class="text-gray-500 hover:text-gray-900 font-medium">Layanan</a>
                    <a href="/#gallery" class="text-gray-500 hover:text-gray-900 font-medium">Galeri</a>
                    <a href="/#team" class="text-gray-500 hover:text-gray-900 font-medium">Tim Kami</a>
                    <a href="/#location" class="text-gray-500 hover:text-gray-900 font-medium">Lokasi</a>
                </div>

                {{-- Navigasi Kanan --}}
                <div class="flex items-center">
                    @auth
                        <div class="hidden sm:flex sm:items-center sm:space-x-8">
                            <a href="{{ route('booking.create') }}" class="text-gray-500 hover:text-gray-900 font-medium">Booking Baru</a>
                            <a href="{{ route('booking.history') }}" class="text-gray-500 hover:text-gray-900 font-medium">Riwayat Booking</a>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>{{ Auth::user()->name }}</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Profile') }}
                                    </x-dropdown-link>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Register</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </header>

    {{-- ======================================================= --}}
    {{-- KONTEN UTAMA HALAMAN                                   --}}
    {{-- ======================================================= --}}
    <main>
        {{ $slot }}
    </main>

    {{-- ======================================================= --}}
    {{-- FOOTER                                                  --}}
    {{-- ======================================================= --}}
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 text-center">
            <a href="{{ route('landing') }}" class="font-black text-2xl" style="font-family: 'Playfair Display', serif;">
                Onesky Hair studio
            </a>
            <p class="mt-4 text-gray-400">Gaya Premium Pria Modern Sejak 2025</p>
            <p class="mt-2 text-sm text-gray-500">© {{ date('Y') }} Onesky Hair Studio Barbershop. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
