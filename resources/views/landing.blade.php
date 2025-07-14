@extends('layouts.app')

@section('title', 'The Mancave Barbershop - Gaya Premium Pria Modern')

@section('content')
    {{-- HERO SECTION --}}
    <section class="relative h-screen bg-black flex items-center justify-center text-white">
        <img src="https://images.pexels.com/photos/2061820/pexels-photo-2061820.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2"
             alt="Suasana Barbershop Mancave" class="absolute w-full h-full object-cover opacity-40">
        <div class="relative z-10 text-center px-4">
            <h1 class="text-4xl md:text-6xl font-black tracking-tight" style="font-family: 'Playfair Display', serif;">Presisi Dalam Setiap Potongan</h1>
            <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-300">Temukan kembali gaya Anda di tangan para ahli. Kami bukan sekadar barbershop, kami adalah destinasi.</p> {{-- Warna teks disesuaikan --}}
            <a href="{{ route('booking.create') }}" class="mt-8 inline-block bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-3 px-10 rounded-full text-lg transition duration-300 transform hover:scale-105">
                Booking Jadwal Anda
            </a>
        </div>
    </section>

    {{-- WHY CHOOSE US? SECTION --}}
    <section id="why-us" class="py-20 bg-gray-800 text-white"> 
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-white">Kenapa Memilih The Mancave?</h2> 
                <p class="mt-4 text-lg text-gray-300">Kami memberikan lebih dari sekadar potongan rambut.</p> 
            </div>
            <div class="mt-16 grid gap-10 md:grid-cols-2 lg:grid-cols-4 text-center">
                <div class="flex flex-col items-center">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-yellow-500 text-gray-900 mb-4"> 
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Barber Berpengalaman</h3>
                    <p class="mt-2 text-gray-300">Tim kami terdiri dari para ahli yang memahami tren dan teknik terkini.</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-yellow-500 text-gray-900 mb-4">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Produk Kualitas Top</h3>
                    <p class="mt-2 text-gray-300">Kami hanya menggunakan produk perawatan rambut pria premium.</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-yellow-500 text-gray-900 mb-4">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Tempat yang Nyaman</h3>
                    <p class="mt-2 text-gray-300">Desain interior yang maskulin dan modern untuk relaksasi total.</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-yellow-500 text-gray-900 mb-4">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Higienis Terjamin</h3>
                    <p class="mt-2 text-gray-300">Kebersihan peralatan dan area kerja adalah prioritas utama kami.</p>
                </div>
            </div>
        </div>
    </section>

    
    <section id="services" class="py-20 bg-gray-900 text-white"> 
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-white">Layanan Unggulan Kami</h2>
            </div>
            <div class="mt-16 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($services as $service)
                    <div class="bg-gray-800 rounded-lg shadow-xl p-8 transform hover:-translate-y-2 transition-transform duration-300"> 
                        <h3 class="text-2xl font-bold text-white">{{ $service->name }}</h3>
                        <p class="mt-3 text-gray-300">{{ $service->description ?? 'Deskripsi tidak tersedia.' }}</p>
                        <div class="mt-6 flex items-center justify-between">
                            <span class="text-2xl font-bold text-gray-100">{{ Number::currency($service->price, 'IDR') }}</span>
                            <span class="text-sm font-medium text-gray-300 bg-gray-700 px-3 py-1 rounded-full">{{ $service->duration_minutes }} menit</span> 
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-center text-gray-300">Layanan akan segera ditambahkan.</p>
                @endforelse
            </div>
        </div>
    </section>


    {{-- GALLERY SECTION --}}
    <section id="gallery" class="py-20 bg-gray-800 text-white"> 
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-extrabold text-white">Galeri Hasil Karya</h2>
            <p class="mt-4 text-lg text-gray-300">Lihat beberapa gaya terbaik yang kami ciptakan.</p>
            <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-4">
                <img src="https://images.pexels.com/photos/3998402/pexels-photo-3998402.jpeg" alt="Gaya rambut 1" class="rounded-lg shadow-md w-full h-full object-cover transform hover:scale-105 transition-transform duration-300">
                <img src="https://images.pexels.com/photos/3998429/pexels-photo-3998429.jpeg" alt="Gaya rambut 2" class="rounded-lg shadow-md w-full h-full object-cover transform hover:scale-105 transition-transform duration-300">
                <img src="https://images.pexels.com/photos/3998413/pexels-photo-3998413.jpeg" alt="Gaya rambut 3" class="rounded-lg shadow-md w-full h-full object-cover transform hover:scale-105 transition-transform duration-300">
                <img src="https://images.pexels.com/photos/3998426/pexels-photo-3998426.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Gaya rambut 4" class="rounded-lg shadow-md w-full h-full object-cover transform hover:scale-105 transition-transform duration-300">
            </div>
        </div>
    </section>

    {{-- TEAM SECTION --}}
    <section id="team" class="py-20 bg-gray-900 text-white"> 
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-extrabold text-white">Tim Profesional Kami</h2>
            <div class="mt-16 grid gap-12 md:grid-cols-3">
                <div class="space-y-4">
                    <img class="mx-auto h-40 w-40 rounded-full object-cover shadow-lg" src="https://images.pexels.com/photos/3764569/pexels-photo-3764569.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Barber 1">
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-white">Kirana "The Fade" Cantika</h3>
                        <p class="text-yellow-500 font-medium">Master Barber - Spesialis Fade</p> 
                        <p class="mt-2 text-gray-300">Dengan 10 tahun pengalaman, Kirana adalah seniman di balik fade yang presisi.</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <img class="mx-auto h-40 w-40 rounded-full object-cover shadow-lg" src="https://images.pexels.com/photos/3764562/pexels-photo-3764562.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Barber 2">
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-white">Budi Santoso</h3>
                        <p class="text-yellow-500 font-medium">Senior Barber - Ahli Klasik</p>
                        <p class="mt-2 text-gray-300">Budi menguasai potongan rambut klasik dan teknik shaving tradisional.</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <img class="mx-auto h-40 w-40 rounded-full object-cover shadow-lg" src="https://images.pexels.com/photos/897262/pexels-photo-897262.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Barber 3">
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-white">Candra Permana</h3>
                        <p class="text-yellow-500 font-medium">Junior Barber - Gaya Modern</p>
                        <p class="mt-2 text-gray-300">Selalu up-to-date dengan tren, Candra siap wujudkan gaya modern Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- TESTIMONIALS --}}
    <section class="py-20 bg-gray-800 text-white"> 
         <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-extrabold text-white">Apa Kata Mereka?</h2>
             <div class="mt-16 grid gap-8 md:grid-cols-2">
                <blockquote class="bg-gray-900 p-8 rounded-lg"> 
                    <p class="text-lg text-gray-200">"Barbershop terbaik di kota! Potongannya selalu pas, tempatnya nyaman banget. Sangat direkomendasikan!"</p>
                    <footer class="mt-4 text-yellow-500 font-bold">★★★★★ - Rian P.</footer>
                </blockquote>
                <blockquote class="bg-gray-900 p-8 rounded-lg">
                    <p class="text-lg text-gray-200">"Timnya profesional dan ramah. Mereka benar-benar mendengarkan apa yang saya mau. Hasilnya tidak pernah mengecewakan."</p>
                    <footer class="mt-4 text-yellow-500 font-bold">★★★★★ - David K.</footer>
                </blockquote>
             </div>
         </div>
    </section>

    {{-- LOCATION & FAQ --}}
    <section id="location" class="py-20 bg-gray-900 text-white"> 
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 px-4 sm:px-6 lg:px-8">
            <div>
                <h2 class="text-3xl font-extrabold text-white">Kunjungi Kami</h2>
                <p class="mt-4 text-lg text-gray-300">Kami siap menyambut Anda.</p>
                <div class="mt-6 space-y-4">
                    <p class="flex items-start text-gray-300"><strong class="w-24 text-white">Alamat:</strong> <span>Jl. Gaya Keren No. 45, Jakarta Selatan</span></p> 
                    <p class="flex items-start text-gray-300"><strong class="w-24 text-white">Jam Buka:</strong> <span>Setiap Hari, 09:00 - 20:00 WIB</span></p>
                    <p class="flex items-start text-gray-300"><strong class="w-24 text-white">WhatsApp:</strong> <a href="https://wa.me/6281234567890" class="text-yellow-500 hover:underline">0812-3456-7890</a></p> 
                </div>
                <div class="mt-6 rounded-lg shadow-md overflow-hidden">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.1956693838275!2d106.82025211476906!3d-6.20876359550742!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f46b1f2b2b2b%3A0x6a2e4a2e4a2e4a2e!2sMonas%20(Monumen%20Nasional)!5e0!3m2!1sen!2sid!4v1678912345678!5m2!1sen!2sid" {{-- Ganti dengan embed Monas --}}
                            width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-white">Tanya Jawab</h2>
                <div class="mt-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-bold text-white">Apakah wajib booking dulu?</h3>
                        <p class="mt-2 text-gray-300">Kami sangat menyarankan untuk booking online agar Anda tidak perlu menunggu. Namun, pelanggan walk-in tetap kami layani jika ada slot yang kosong.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Bagaimana jika saya terlambat?</h3>
                        <p class="mt-2 text-gray-300">Kami memberikan toleransi keterlambatan 10 menit. Jika lebih dari itu, kami mungkin harus menggeser jadwal Anda ke antrean berikutnya yang tersedia.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Metode pembayaran apa saja yang diterima?</h3>
                        <p class="mt-2 text-gray-300">Kami menerima pembayaran tunai, transfer bank, QRIS, dan kartu debit/kredit.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection