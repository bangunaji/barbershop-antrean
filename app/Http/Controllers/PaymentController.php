<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use App\Models\Booking;

class PaymentController extends Controller
{
    // Mendapatkan Snap Token (opsional jika belum generate di BookingController)
    public function getSnapToken($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Buat parameter transaksi
        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $booking->id . '-' . time(),
                'gross_amount' => $booking->total_price,
            ],
            'customer_details' => [
                'first_name' => $booking->customer_name,
                'email' => $booking->email ?? 'dummy@mail.com',
            ],
        ];

        \Config::set('midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
\Config::set('midtrans.client_key', env('MIDTRANS_CLIENT_KEY'));
\Config::set('midtrans.is_production', env('MIDTRANS_IS_PRODUCTION', false));
\Config::set('midtrans.sanitize', true);
\Config::set('midtrans.use_3ds', true);

\Config::set('midtrans.server_key', config('midtrans.server_key'));
\Config::set('midtrans.client_key', config('midtrans.client_key'));

\Midtrans\Config::$serverKey = config('midtrans.server_key');
\Midtrans\Config::$isProduction = config('midtrans.is_production');
\Midtrans\Config::$isSanitized = config('midtrans.sanitize');
\Midtrans\Config::$is3ds = config('midtrans.use_3ds');


        $snapToken = Snap::getSnapToken($params);

        // Simpan order_id sementara
        $booking->midtrans_order_id = $params['transaction_details']['order_id'];
        $booking->save();

        return response()->json(['snap_token' => $snapToken]);
    }

    // Handler untuk callback (webhook) Midtrans
    public function handleCallback(Request $request)
    {
        $notif = new Notification();

        $transaction = $notif->transaction_status;
        $order_id = $notif->order_id;
        $transaction_id = $notif->transaction_id;

        $booking = Booking::where('midtrans_order_id', $order_id)->first();

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $booking->midtrans_transaction_id = $transaction_id;

        if (in_array($transaction, ['capture', 'settlement'])) {
            $booking->payment_status = 'paid';
        } elseif ($transaction == 'pending') {
            $booking->payment_status = 'pending';
        } elseif (in_array($transaction, ['deny', 'expire', 'cancel'])) {
            $booking->payment_status = 'failed';
        }

        $booking->save();

        return response()->json(['message' => 'Payment status updated']);
    }

    // Handler untuk finish page setelah sukses bayar
    public function paymentSuccess($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        // Update jika belum diubah via callback
        if ($booking->payment_status !== 'paid') {
            $booking->payment_status = 'paid';
            $booking->save();
        }

        return redirect()->route('dashboard')->with('success', 'Pembayaran berhasil!');
    }
}
