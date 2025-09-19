@extends('layouts.app')

@section('title', 'Refund Booking')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-lg bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="bg-red-600 text-white text-lg font-bold px-6 py-4">
            Form Permintaan Refund
        </div>
        <div class="px-6 py-6">
            <p class="text-sm text-gray-300 mb-1">Kode Booking:
                <span class="font-semibold text-white">#{{ $booking->id }}</span>
            </p>
            <p class="text-sm text-gray-300 mb-4">Total Pembayaran:
                <span class="font-semibold text-green-400">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
            </p>

            @if(session('error'))
                <div class="bg-red-700 text-white px-4 py-2 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('booking.refund.submit', $booking->id) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="refund_reason" class="block text-white font-medium mb-1">Alasan Refund</label>
                    <textarea name="refund_reason" id="refund_reason" rows="4"
                        class="w-full rounded-md bg-gray-700 text-white p-3 focus:outline-none focus:ring-2 focus:ring-red-500
                        @error('refund_reason') border border-red-500 @enderror"
                        required>{{ old('refund_reason') }}</textarea>
                    @error('refund_reason')
                        <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded font-semibold transition">
                        Kirim Permintaan Refund
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
