{{-- resources/views/booking/history.blade.php --}}
@extends('layouts.app')

@section('title', 'Riwayat Booking Anda')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"> 
            <div class="p-6 bg-gray-800 border-b border-gray-700"> 
                <h3 class="text-2xl font-bold text-white mb-6 text-center">Riwayat Booking Anda</h3> 

                @if (session('success'))
                    <div class="bg-green-800 border border-green-700 text-green-200 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Sukses!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-800 border border-red-700 text-red-200 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @forelse ($bookings as $booking)
                    <div class="border rounded-lg p-6 mb-4 bg-gray-900 shadow-sm"> 
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="text-xl font-semibold text-gray-100">Booking: {{ $booking->queue_number }}</h4> 
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($booking->booking_status === 'active') bg-blue-800 text-blue-200
                                @elseif($booking->booking_status === 'completed') bg-green-800 text-green-200
                                @elseif($booking->booking_status === 'cancelled') bg-red-800 text-red-200
                                @endif"> 
                                {{ ucfirst($booking->booking_status) }}
                            </span>
                        </div>
                        <p class="text-gray-300 mb-2">Tanggal: <span class="font-medium">{{ $booking->booking_date->translatedFormat('l, d F Y') }}</span></p> 
                        <p class="text-gray-300 mb-2">Jam: <span class="font-medium">{{ $booking->booking_time ? $booking->booking_time->format('H:i') : '-' }}</span></p>
                        
                        <div class="mb-2">
                            <p class="font-medium text-gray-200">Layanan:</p> 
                            <ul class="list-disc list-inside text-gray-300 ml-4"> 
                                @foreach ($booking->services as $service)
                                    <li>{{ $service->name }} ({{ Number::currency($service->price, 'IDR') }})</li>
                                @endforeach
                            </ul>
                        </div>
                        <p class="text-gray-100 font-bold">Total Harga: {{ Number::currency($booking->total_price, 'IDR') }}</p> 

                        <div class="mt-4 flex justify-end space-x-2">
                            <a href="{{ route('booking.show', $booking->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-100 uppercase tracking-widest hover:bg-gray-600 transition ease-in-out duration-150"> 
                                Lihat Detail
                            </a>
                            @if ($booking->booking_status === 'active')
                                <a href="{{ route('booking.edit', $booking->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150"> 
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('booking.destroy', $booking->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini? Aksi ini tidak dapat dibatalkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition ease-in-out duration-150"> 
                                        Batalkan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center p-8 bg-gray-900 rounded-lg"> 
                        <p class="text-gray-300 text-lg">Anda belum memiliki riwayat booking.</p>
                        <a href="{{ route('booking.create') }}" class="mt-4 inline-block text-yellow-500 hover:text-yellow-600 font-medium">Buat Booking Sekarang</a> 
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection