<x-layouts.main>
    <div class="py-12 bg-white">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h1 class="text-4xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Konfirmasi Booking Anda</h1>
                <p class="text-gray-600 mt-2">Silakan periksa kembali detail booking Anda sebelum disimpan.</p>
            </div>

            <form method="POST" action="{{ route('booking.save') }}">
                
                @csrf

                @foreach ($selectedServices as $service)
                    <input type="hidden" name="services[]" value="{{ $service->id }}">
                @endforeach
                <input type="hidden" name="booking_date" value="{{ $bookingDate->toDateString() }}">
                <input type="hidden" name="start_time" value="{{ $startTime->format('H:i') }}">

                <div class="bg-gray-50 p-8 rounded-lg shadow-inner space-y-6 border">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-500 uppercase tracking-wider">Tanggal & Waktu</h3>
                        <p class="text-2xl font-bold">{{ $bookingDate->format('l, d F Y') }}</p>
                        <p class="text-xl text-gray-700">Pukul {{ $startTime->format('H:i') }} - {{ $endTime->format('H:i') }}</p>
                    </div>

                    <div class="pt-4 border-t">
                        <h3 class="text-lg font-semibold text-gray-500 uppercase tracking-wider">Layanan Dipilih</h3>
                        <ul class="list-disc pl-5 mt-2 space-y-1 text-gray-700">
                            @foreach ($selectedServices as $service)
                                <li>{{ $service->name }} ({{ $service->duration_in_minutes }} menit)</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="pt-4 border-t text-right">
                        <p class="text-gray-500">Total Harga</p>
                        <p class="text-3xl font-extrabold text-gray-900">{{ Number::currency($totalPrice, 'IDR') }}</p>
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <button type="submit" class="inline-flex items-center px-10 py-4 bg-yellow-500 border border-transparent rounded-md font-semibold text-base text-gray-900 uppercase tracking-widest hover:bg-yellow-600">
                        Konfirmasi & Simpan Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.main>
