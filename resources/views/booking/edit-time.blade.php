<x-layouts.main>
    <div class="py-12 bg-white">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-extrabold text-gray-900" style="font-family: 'Playfair Display', serif;">
                    Ubah Jadwal Booking
                </h1>
                <p class="mt-4 text-lg text-gray-500">
                    Pilih jadwal baru untuk booking Anda pada tanggal: 
                    <span class="font-bold text-yellow-600">{{ $bookingDate->format('d F Y') }}</span>
                </p>
            </div>

            <form method="POST" action="{{ route('booking.update') }}">
                @csrf

                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                <input type="hidden" name="booking_date" value="{{ $bookingDate->toDateString() }}">

                <div class="bg-gray-50 p-6 rounded-lg shadow-inner border border-gray-200 mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Ringkasan Booking:</h3>
                    <ul class="space-y-2">
                        @foreach ($booking->services_snapshot as $name => $price)
                            <li class="flex justify-between">
                                <span>{{ $name }}</span>
                                <span class="font-medium">{{ Number::currency($price, 'IDR') }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-4 pt-4 border-t border-gray-300 space-y-2">
                        <div class="flex justify-between font-semibold">
                            <span>Total Durasi:</span>
                            <span>{{ $booking->total_duration_in_minutes }} menit</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total Harga:</span>
                            <span>{{ Number::currency($booking->total_price, 'IDR') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Slot Waktu Tersedia --}}
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 text-center mb-6">Pilih Waktu Tersedia</h3>

                    @if (empty($availableTimeSlots))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-center" role="alert">
                            <p class="font-bold">Jadwal Penuh!</p>
                            <p class="text-sm">Tidak ada jadwal tersedia untuk tanggal ini.</p>
                            <a href="{{ route('booking.history') }}" class="mt-4 inline-block bg-gray-800 text-white font-bold py-2 px-4 rounded hover:bg-gray-700">Kembali</a>
                        </div>
                    @else
                        <div class="grid grid-cols-4 sm:grid-cols-6 gap-3">
                            @foreach ($availableTimeSlots as $slot)
                                <label class="cursor-pointer">
                                    <input type="radio" name="start_time" value="{{ $slot }}" class="peer sr-only" required>
                                    <div class="text-center font-semibold p-3 border rounded-lg transition-all peer-checked:bg-yellow-500 peer-checked:text-gray-900 peer-checked:border-yellow-500 hover:border-gray-500">
                                        {{ $slot }}
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <div class="mt-10 text-center">
                            <x-primary-button class="!py-3 !px-10 !text-base">
                                Simpan Perubahan
                            </x-primary-button>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-layouts.main>
