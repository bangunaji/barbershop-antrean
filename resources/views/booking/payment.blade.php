@extends('layouts.app')

@section('title', 'Pembayaran Booking')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md bg-gray-800 rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-white text-center mb-6">Konfirmasi Pembayaran</h2>

        <div class="space-y-3 text-gray-300">
            <p><span class="font-semibold text-white">Nama:</span> {{ $booking->customer_name }}</p>
            <p><span class="font-semibold text-white">Tanggal:</span> {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y') }}</p>
            <p><span class="font-semibold text-white">Waktu:</span> {{ $booking->booking_time ?? '-' }}</p>
            <p><span class="font-semibold text-white">Total Harga:</span> 
                <span class="text-green-400 font-bold">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
            </p>
        </div>

        <div class="mt-6">
            <button id="pay-button"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-md font-semibold transition">
                Bayar Sekarang
            </button>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

<script type="text/javascript">
    document.getElementById('pay-button').addEventListener('click', function () {
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function (result) {
                alert("Pembayaran berhasil!");
                window.location.href = "{{ route('payment.success', $booking->id) }}";
            },
            onPending: function (result) {
                alert("Menunggu pembayaran.");
            },
            onError: function (result) {
                alert("Pembayaran gagal!");
            },
            onClose: function () {
                alert("Kamu menutup popup tanpa menyelesaikan pembayaran.");
            }
        });
    });
</script>
@endsection
    