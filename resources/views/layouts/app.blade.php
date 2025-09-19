{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Onesky Hair Studio')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&family=playfair-display:700,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-900 text-gray-100"> {{-- bg-gray-900 dan text-gray-100 untuk dark mode --}}

    {{-- NAVIGASI --}}
    <header class="bg-gray-950 shadow-lg sticky top-0 z-50"> {{-- Warna header lebih gelap --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    {{-- Logo --}}
                    <a href="{{ route('landing') }}" class="font-black text-2xl text-gray-50" style="font-family: 'Playfair Display', serif;"> {{-- Warna logo putih --}}
                        Barbershop Onesky Hair Studio
                    </a>
                </div>

                {{-- Navigasi Tengah --}}
                <div class="hidden sm:flex sm:items-center sm:space-x-8">
                    <a href="{{ route('landing') }}#" class="text-gray-300 hover:text-white font-medium">Home</a> {{-- Warna teks disesuaikan --}}
                    <a href="{{ route('landing') }}#services" class="text-gray-300 hover:text-white font-medium">Layanan</a> {{-- Warna teks disesuaikan --}}
                    <a href="{{ route('landing') }}#gallery" class="text-gray-300 hover:text-white font-medium">Galeri</a>
                    <a href="{{ route('landing') }}#team" class="text-gray-300 hover:text-white font-medium">Tim Kami</a>
                    <a href="{{ route('landing') }}#location" class="text-gray-300 hover:text-white font-medium">Lokasi</a>
                </div>

                {{-- Navigasi Kanan (Login/Register atau Menu User) --}}
                <div class="flex items-center">
                    @auth
                        <div class="hidden sm:flex sm:items-center sm:space-x-8">
                            <a href="{{ route('booking.create') }}" class="text-gray-300 hover:text-white font-medium">Booking Baru</a>
                            <a href="{{ route('booking.history') }}" class="text-gray-300 hover:text-white font-medium">Riwayat Booking</a>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-300 bg-gray-950 hover:text-white focus:outline-none transition ease-in-out duration-150"> {{-- Warna dropdown trigger --}}
                                        <div>{{ Auth::user()->name }}</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    {{-- Dropdown content --}}
                                    <div class="bg-gray-800 rounded-md shadow-lg py-1"> {{-- Warna dropdown menu --}}
                                        

                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white"> {{-- Warna dropdown link --}}
                                                {{ __('Log Out') }}
                                            </x-dropdown-link>
                                        </form>
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-white">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-gray-900 uppercase tracking-widest hover:bg-yellow-600 hover:shadow-lg transition duration-300">Register</a> {{-- Warna tombol register --}}
                        @endif
                    @endauth

                    {{-- Hamburger Menu (Mobile) --}}
                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-800 focus:outline-none focus:bg-gray-800 focus:text-white transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Responsive Navigation Menu (Mobile) --}}
        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gray-900 pb-2"> {{-- Warna background responsive menu --}}
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('booking.create')" :active="request()->routeIs('booking.create')" class="block px-4 py-2 text-base font-medium text-gray-300 hover:text-white hover:bg-gray-800">
                    Booking Baru
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('booking.history')" :active="request()->routeIs('booking.history')" class="block px-4 py-2 text-base font-medium text-gray-300 hover:text-white hover:bg-gray-800">
                    Riwayat Booking
                </x-responsive-nav-link>
                @guest
                <x-responsive-nav-link :href="route('login')" class="block px-4 py-2 text-base font-medium text-gray-300 hover:text-white hover:bg-gray-800">
                    Log In
                </x-responsive-nav-link>
                @if (Route::has('register'))
                <x-responsive-nav-link :href="route('register')" class="block px-4 py-2 text-base font-medium text-gray-300 hover:text-white hover:bg-gray-800">
                    Register
                </x-responsive-nav-link>
                @endif
                @endguest
            </div>

            @auth
            <div class="pt-4 pb-1 border-t border-gray-700"> {{-- Border warna gelap --}}
                <div class="px-4">
                    <div class="font-medium text-base text-gray-50">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')" class="block px-4 py-2 text-base font-medium text-gray-300 hover:text-white hover:bg-gray-800">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-base font-medium text-gray-300 hover:text-white hover:bg-gray-800">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
            @endauth
        </div>
    </header>


    <main class="min-h-screen">
        @yield('content')
    </main>


    {{-- FOOTER --}}
    <footer class="bg-gray-950 text-white bottom-0"> {{-- Warna footer lebih gelap --}}
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 text-center">
            <a href="{{ route('landing') }}" class="font-black text-2xl" style="font-family: 'Playfair Display', serif;">
                Onesky Hair Studio
            </a>
            <p class="mt-4 text-gray-400">Gaya Premium Pria Modern Sejak 2025</p>
            <p class="mt-2 text-sm text-gray-500">© {{ date('Y') }} Onesky Hair Studio. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="//unpkg.com/alpinejs" defer></script>
    @stack('scripts')

</body>
</html>