<x-layouts.main>
    <div class="py-12 bg-white">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-extrabold text-gray-900" style="font-family: 'Playfair Display', serif;">Pilih Waktu Tersedia</h1>
                <p class="mt-4 text-lg text-gray-500">
                    Jadwal untuk tanggal: <span class="font-bold text-yellow-600">{{ $bookingDate->format('d F Y') }}</span>
                </p>
            </div>

<form method="POST" action="{{ route('booking.confirm') }}">
                @csrf
                
                
                @foreach ($selectedServices as $service)
                    <input type="hidden" name="services[]" value="{{ $service->id }}">
                @endforeach
                <input type="hidden" name="booking_date" value="{{ $bookingDate->toDateString() }}">

                
                <div class="bg-gray-50 p-6 rounded-lg shadow-inner border border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Ringkasan Pilihan Anda:</h3>
                    <ul class="space-y-2">
                        @foreach ($selectedServices as $service)
                            <li class="flex justify-between">
                                <span>{{ $service->name }}</span>
                                <span class="font-medium">{{ Number::currency($service->price, 'IDR') }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-4 pt-4 border-t border-gray-300 space-y-2">
                        <div class="flex justify-between font-semibold">
                            <span>Total Durasi:</span>
                            <span>{{ $totalDuration }} menit</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total Harga:</span>
                            <span>{{ Number::currency($totalPrice, 'IDR') }}</span>
                        </div>
                    </div>
                </div>

                
                <div class="mt-8">
                    <h3 class="text-2xl font-bold text-gray-900 text-center mb-6">Pilih Waktu Tersedia</h3>

                    @if (empty($availableTimeSlots))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-center" role="alert">
                            <p class="font-bold">Jadwal Penuh!</p>
                            <p class="text-sm">Mohon maaf, tidak ada jadwal yang tersedia untuk tanggal dan durasi layanan yang Anda pilih.</p>
                            <a href="{{ route('booking.create') }}" class="mt-4 inline-block bg-gray-800 text-white font-bold py-2 px-4 rounded hover:bg-gray-700">Coba Tanggal Lain</a>
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
                            <button type="submit" class="inline-flex items-center px-8 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Lanjutkan ke Konfirmasi
                            </button>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-layouts.main>
