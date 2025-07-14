@extends('layouts.app')

@section('title', 'Detail Booking ' . $booking->queue_number)

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"> 
            <div class="p-6 bg-gray-800 border-b border-gray-700"> 
                <h3 class="text-2xl font-bold text-white mb-6 text-center">Detail Booking</h3> 

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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 text-gray-200"> 
                    <div>
                        <p class="text-sm font-medium text-gray-400">Kode Antrean:</p>
                        <p class="text-lg font-semibold text-white">{{ $booking->queue_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-400">Nama Pelanggan:</p>
                        <p class="text-lg font-semibold text-white">{{ $booking->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-400">Tanggal Booking:</p>
                        <p class="text-lg font-semibold text-white">{{ $booking->booking_date->translatedFormat('l, d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-400">Jam Booking:</p>
                        <p class="text-lg font-semibold text-white">{{ $booking->booking_time ? $booking->booking_time->format('H:i') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-400">Status Booking:</p>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($booking->booking_status === 'active') bg-blue-800 text-blue-200
                            @elseif($booking->booking_status === 'completed') bg-green-800 text-green-200
                            @elseif($booking->booking_status === 'cancelled') bg-red-800 text-red-200
                            @endif">
                            {{ ucfirst($booking->booking_status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-400">Status Kehadiran:</p>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($booking->arrival_status === 'waiting') bg-gray-700 text-gray-300
                            @elseif($booking->arrival_status === 'arrived') bg-green-800 text-green-200
                            @elseif($booking->arrival_status === 'late') bg-yellow-800 text-yellow-200
                            @endif">
                            {{ ucfirst($booking->arrival_status) }}
                        </span>
                    </div>
                </div>

                @if ($booking->booking_status === 'active')
                    <div class="mt-6 p-4 bg-yellow-900 border border-yellow-800 rounded-lg text-center text-yellow-100"> 
                        <p class="text-lg font-semibold">Anda berada di antrean nomor <span class="text-2xl font-bold">{{ $queuePosition }}</span>.</p>
                        <p class="text-sm mt-2">Perkiraan waktu tunggu: <span class="font-bold">{{ $estimatedWaitTime }}</span></p>
                        <p class="text-xs mt-1">Status antrean diperbarui secara real-time.</p>
                    </div>
                @elseif ($booking->booking_status === 'completed')
                    <div class="mt-6 p-4 bg-green-900 border border-green-800 rounded-lg text-center text-green-100">
                        <p class="text-lg font-semibold">Booking ini telah <span class="font-bold">Selesai</span>.</p>
                    </div>
                @elseif ($booking->booking_status === 'cancelled')
                    <div class="mt-6 p-4 bg-red-900 border border-red-800 rounded-lg text-center text-red-100">
                        <p class="text-lg font-semibold">Booking ini telah <span class="font-bold">Dibatalkan</span>.</p>
                    </div>
                @endif

                <div class="mb-6">
                    <p class="text-sm font-medium text-gray-400">Layanan yang Dipilih:</p>
                    <ul class="list-disc list-inside text-gray-200 ml-4 mt-2">
                        @foreach ($booking->services as $service)
                            <li>{{ $service->name }} ({{ Number::currency($service->price, 'IDR') }}) - {{ $service->duration_minutes }} menit</li>
                        @endforeach
                    </ul>
                </div>

                <p class="text-sm font-medium text-gray-400">Total Harga:</p>
                <p class="text-xl font-bold text-white">{{ Number::currency($booking->total_price, 'IDR') }}</p>

                @if ($booking->notes)
                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-400">Catatan:</p>
                        <p class="text-gray-200">{{ $booking->notes }}</p>
                    </div>
                @endif

                <div class="mt-8 flex justify-end">
                    <a href="{{ route('booking.history') }}" class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 transition ease-in-out duration-150">
                        Kembali ke Riwayat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection