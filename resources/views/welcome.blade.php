<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Onesky Hair Studio</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&family=playfair-display:700,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">

    
       <header class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    
                    <a href="{{ route('landing') }}" class="font-black text-2xl text-gray-800" style="font-family: 'Playfair Display', serif;">
                        Onesky Hair Studio
                    </a>
                </div>

                
                <div class="hidden sm:flex sm:items-center sm:space-x-8">
                    <a href="/#services" class="text-gray-500 hover:text-gray-900 font-medium">Layanan</a>
                    <a href="/#gallery" class="text-gray-500 hover:text-gray-900 font-medium">Galeri</a>
                    <a href="/#team" class="text-gray-500 hover:text-gray-900 font-medium">Tim Kami</a>
                    <a href="/#location" class="text-gray-500 hover:text-gray-900 font-medium">Lokasi</a>
                </div>

                
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
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
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


    <main>
        
        <section class="relative h-[600px] bg-gray-900 flex items-center justify-center text-white">
            <img src="https://images.pexels.com/photos/2061820/pexels-photo-2061820.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2"
                 alt="Suasana Barbershop Onesky" class="absolute w-full h-full object-cover opacity-40">
            <div class="relative z-10 text-center px-4">
                <h1 class="text-4xl md:text-6xl font-black tracking-tight" style="font-family: 'Playfair Display', serif;">Presisi Dalam Setiap Potongan</h1>
                <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-200">Temukan kembali gaya Anda di tangan para ahli. Kami bukan sekadar barbershop, kami adalah destinasi.</p>
                <a href="{{ route('booking.create') }}" class="mt-8 inline-block bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-3 px-10 rounded-full text-lg transition duration-300 transform hover:scale-105">
                    Booking Jadwal Anda
                </a>
            </div>
        </section>

        
        <section id="why-us" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-gray-900">Kenapa Memilih Onesky Hair Studio</h2>
                    <p class="mt-4 text-lg text-gray-500">Kami memberikan lebih dari sekadar potongan rambut.</p>
                </div>
                <div class="mt-16 grid gap-10 md:grid-cols-2 lg:grid-cols-4 text-center">
                    <div class="flex flex-col items-center">
                        <div class="flex items-center justify-center h-16 w-16 rounded-full bg-yellow-500 text-white mb-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Barber Berpengalaman</h3>
                        <p class="mt-2 text-gray-500">Tim kami terdiri dari para ahli yang memahami tren dan teknik terkini.</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="flex items-center justify-center h-16 w-16 rounded-full bg-yellow-500 text-white mb-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Produk Kualitas Top</h3>
                        <p class="mt-2 text-gray-500">Kami hanya menggunakan produk perawatan rambut pria premium.</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="flex items-center justify-center h-16 w-16 rounded-full bg-yellow-500 text-white mb-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Tempat yang Nyaman</h3>
                        <p class="mt-2 text-gray-500">Desain interior yang maskulin dan modern untuk relaksasi total.</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="flex items-center justify-center h-16 w-16 rounded-full bg-yellow-500 text-white mb-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Higienis Terjamin</h3>
                        <p class="mt-2 text-gray-500">Kebersihan peralatan dan area kerja adalah prioritas utama kami.</p>
                    </div>
                </div>
            </div>
        </section>

        
        <section id="services" class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-gray-900">Layanan Unggulan Kami</h2>
                </div>
                <div class="mt-16 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    @forelse ($services as $service)
                        <div class="bg-white rounded-lg shadow-xl p-8 transform hover:-translate-y-2 transition-transform duration-300">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $service->name }}</h3>
                            <p class="mt-3 text-gray-600">{{ $service->description ?? 'Deskripsi tidak tersedia.' }}</p>
                            <div class="mt-6 flex items-center justify-between">
                                <span class="text-2xl font-bold text-gray-800">{{ Number::currency($service->price, 'IDR') }}</span>
                                <span class="text-sm font-medium text-gray-500 bg-gray-100 px-3 py-1 rounded-full">{{ $service->duration_in_minutes }} menit</span>
                            </div>
                        </div>
                    @empty
                        <p class="col-span-full text-center text-gray-500">Layanan akan segera ditambahkan.</p>
                    @endforelse
                </div>
            </div>
        </section>


        
        <section id="gallery" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Galeri Hasil Karya</h2>
                <p class="mt-4 text-lg text-gray-500">Lihat beberapa gaya terbaik yang kami ciptakan.</p>
                <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-4">
                    <img src="https://images.pexels.com/photos/7631320/pexels-photo-7631320.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Gaya rambut 1" class="rounded-lg shadow-md w-full h-full object-cover transform hover:scale-105 transition-transform duration-300">
                    <img src="https://images.pexels.com/photos/5472810/pexels-photo-5472810.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Gaya rambut 2" class="rounded-lg shadow-md w-full h-full object-cover transform hover:scale-105 transition-transform duration-300">
                    <img src="https://images.pexels.com/photos/7631498/pexels-photo-7631498.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Gaya rambut 3" class="rounded-lg shadow-md w-full h-full object-cover transform hover:scale-105 transition-transform duration-300">
                    <img src="https://images.pexels.com/photos/3998426/pexels-photo-3998426.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Gaya rambut 4" class="rounded-lg shadow-md w-full h-full object-cover transform hover:scale-105 transition-transform duration-300">
                </div>
            </div>
        </section>

        
        <section id="team" class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Tim Profesional Kami</h2>
                <div class="mt-16 grid gap-12 md:grid-cols-3">
                    <div class="space-y-4">
                        <img class="mx-auto h-40 w-40 rounded-full object-cover shadow-lg" src="https://images.pexels.com/photos/3764569/pexels-photo-3764569.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Barber 1">
                        <div class="space-y-2">
                            <h3 class="text-xl font-bold">Andi "The Fade" Wijaya</h3>
                            <p class="text-yellow-600 font-medium">Master Barber - Spesialis Fade</p>
                            <p class="text-gray-500">Dengan 10 tahun pengalaman, Andi adalah seniman di balik fade yang presisi.</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <img class="mx-auto h-40 w-40 rounded-full object-cover shadow-lg" src="https://images.pexels.com/photos/3764562/pexels-photo-3764562.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Barber 2">
                        <div class="space-y-2">
                            <h3 class="text-xl font-bold">Budi Santoso</h3>
                            <p class="text-yellow-600 font-medium">Senior Barber - Ahli Klasik</p>
                            <p class="text-gray-500">Budi menguasai potongan rambut klasik dan teknik shaving tradisional.</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <img class="mx-auto h-40 w-40 rounded-full object-cover shadow-lg" src="https://images.pexels.com/photos/897262/pexels-photo-897262.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Barber 3">
                        <div class="space-y-2">
                            <h3 class="text-xl font-bold">Candra Permana</h3>
                            <p class="text-yellow-600 font-medium">Junior Barber - Gaya Modern</p>
                            <p class="text-gray-500">Selalu up-to-date dengan tren, Candra siap wujudkan gaya modern Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        
        <section class="py-20 bg-gray-900 text-white">
             <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-extrabold">Apa Kata Mereka?</h2>
                 <div class="mt-16 grid gap-8 md:grid-cols-2">
                    <blockquote class="bg-gray-800 p-8 rounded-lg">
                        <p class="text-lg">"Barbershop terbaik di kota! Potongannya selalu pas, tempatnya nyaman banget. Sangat direkomendasikan!"</p>
                        <footer class="mt-4 text-yellow-400 font-bold">★★★★★ - Rian P.</footer>
                    </blockquote>
                    <blockquote class="bg-gray-800 p-8 rounded-lg">
                        <p class="text-lg">"Timnya profesional dan ramah. Mereka benar-benar mendengarkan apa yang saya mau. Hasilnya tidak pernah mengecewakan."</p>
                        <footer class="mt-4 text-yellow-400 font-bold">★★★★★ - David K.</footer>
                    </blockquote>
                 </div>
             </div>
        </section>

        
        <section id="location" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 px-4 sm:px-6 lg:px-8">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900">Kunjungi Kami</h2>
                    <p class="mt-4 text-lg text-gray-500">Kami siap menyambut Anda.</p>
                    <div class="mt-6 space-y-4">
                        <p class="flex items-start"><strong class="w-24">Alamat:</strong> <span>Jl. Gaya Keren No. 45, Jakarta Selatan</span></p>
                        <p class="flex items-start"><strong class="w-24">Jam Buka:</strong> <span>Setiap Hari, 09:00 - 20:00 WIB</span></p>
                        <p class="flex items-start"><strong class="w-24">WhatsApp:</strong> <a href="https://wa.me/6281234567890" class="text-yellow-600 hover:underline">0812-3456-7890</a></p>
                    </div>
                    <div class="mt-6 rounded-lg shadow-md overflow-hidden">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3973.8904769110027!2d105.3075982850293!3d-5.121346316314257!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e40bbecc542c831%3A0xd3769f7323b73324!2sOnesky%20barber!5e0!3m2!1sid!2sid!4v1754475561182!5m2!1sid!2sid"
                                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900">Tanya Jawab</h2>
                    <div class="mt-6 space-y-6">
                        <div>
                            <h3 class="text-lg font-bold">Apakah wajib booking dulu?</h3>
                            <p class="mt-2 text-gray-600">Kami sangat menyarankan untuk booking online agar Anda tidak perlu menunggu. Namun, pelanggan walk-in tetap kami layani jika ada slot yang kosong.</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">Bagaimana jika saya terlambat?</h3>
                            <p class="mt-2 text-gray-600">Kami memberikan toleransi keterlambatan 10 menit. Jika lebih dari itu, kami mungkin harus menggeser jadwal Anda ke antrean berikutnya yang tersedia.</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">Metode pembayaran apa saja yang diterima?</h3>
                            <p class="mt-2 text-gray-600">Kami menerima pembayaran tunai, transfer bank, QRIS, dan kartu debit/kredit.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>


    
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 text-center">
            <a href="{{ route('landing') }}" class="font-black text-2xl" style="font-family: 'Playfair Display', serif;">
                Onesky Hair
            </a>
            <p class="mt-4 text-gray-400">Gaya Premium Pria Modern Sejak 2024</p>
            <p class="mt-2 text-sm text-gray-500">&copy; {{ date('Y') }} Onesky Hair Studio. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>