<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\RefundRequest;
use Illuminate\Http\Request;

class AdminRefundController extends Controller
{
    public function index()
    {
        $refunds = RefundRequest::with('booking.user')->orderBy('created_at', 'desc')->get();
    return view('admin.refunds.index', compact('refunds'));
    }

    public function approve($id)
    {
        $booking = Booking::findOrFail($id);

        // Ubah status menjadi approved dan catat waktu refund
        $booking->update([
            'refund_status' => 'approved',
            'refunded_at' => now(),
        ]);

        return back()->with('success', 'Refund disetujui. Silakan transfer dana secara manual ke pelanggan.');
    }

    public function reject($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->update([
            'refund_status' => 'rejected',
        ]);

        return back()->with('success', 'Refund ditolak.');
    }
}
