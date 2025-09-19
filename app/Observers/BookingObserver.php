<?php

namespace App\Observers;

use App\Models\Booking;
use Illuminate\Support\Carbon; 

class BookingObserver
{
    public function created(Booking $booking): void
    {
        
        $bookingDate = $booking->booking_date->setTimezone(config('app.timezone'));
        $bookingTime = $booking->booking_time ? $booking->booking_time->setTimezone(config('app.timezone')) : null;

    }

    public function updated(Booking $booking): void
    {
        $changes = $booking->getChanges();
        $original = $booking->getOriginal();

        if (empty($changes)) {
            return;
        }

        $description = 'Admin memperbarui booking ' . $booking->customer_name . ' (Kode: ' . $booking->queue_number . '). ';
        $detailedChanges = [];

        if (isset($changes['arrival_status']) || isset($changes['booking_status'])) {
            $oldArrival = $original['arrival_status'] ?? 'N/A';
            $newArrival = $changes['arrival_status'] ?? $oldArrival;

            $oldBookingStatus = $original['booking_status'] ?? 'N/A';
            $newBookingStatus = $changes['booking_status'] ?? $oldBookingStatus;

            $statusMap = [
                'waiting' => 'menunggu', 'arrived' => 'datang', 'late' => 'terlambat',
                'completed' => 'selesai', 'cancelled' => 'dibatalkan', 'active' => 'aktif'
            ];

            if ($oldArrival !== $newArrival) {
                $detailedChanges[] = "Status kehadiran berubah dari '" . ($statusMap[$oldArrival] ?? $oldArrival) . "' menjadi '" . ($statusMap[$newArrival] ?? $newArrival) . "'.";
            }
            if ($oldBookingStatus !== $newBookingStatus) {
                $detailedChanges[] = "Status booking berubah dari '" . ($statusMap[$oldBookingStatus] ?? $oldBookingStatus) . "' menjadi '" . ($statusMap[$newBookingStatus] ?? $newBookingStatus) . "'.";
            }

            if ($newBookingStatus === 'completed') {
                $description = 'Admin MENYELESAIKAN service ' . $booking->customer_name . ' (Kode: ' . $booking->queue_number . ') pada tanggal ' . $booking->updated_at->setTimezone(config('app.timezone'))->format('d M Y H:i') . '.';
            } elseif ($newBookingStatus === 'cancelled') {
                $description = 'Admin MEMBATALKAN booking ' . $booking->customer_name . ' (Kode: ' . $booking->queue_number . ') pada tanggal ' . $booking->updated_at->setTimezone(config('app.timezone'))->format('d M Y H:i') . '.';
            } elseif ($newArrival === 'arrived') {
                $description = 'Admin menandai ' . $booking->customer_name . ' (Kode: ' . $booking->queue_number . ') telah HADIR.';
            } elseif ($newArrival === 'late') {
                $description = 'Admin menandai ' . $booking->customer_name . ' (Kode: ' . $booking->queue_number . ') TERLAMBAT.';
            }
        }

        if (isset($changes['sort_order'])) {
            $oldSortOrder = $original['sort_order'] ?? 'N/A';
            $newSortOrder = $changes['sort_order'] ?? 'N/A';
            $description = 'Admin MENGUBAH URUTAN booking ' . $booking->customer_name . ' (Kode: ' . $booking->queue_number . ') dari posisi ' . $oldSortOrder . ' menjadi ' . $newSortOrder . '.';
        }
        
        if (isset($changes['booking_time'])) {
            
            $oldTime = $original['booking_time'] ? \Illuminate\Support\Carbon::parse($original['booking_time'])->setTimezone(config('app.timezone'))->format('H:i') : '-';
            $newTime = $changes['booking_time'] ? \Illuminate\Support\Carbon::parse($changes['booking_time'])->setTimezone(config('app.timezone'))->format('H:i') : '-';
            $detailedChanges[] = "Jam booking diubah dari '" . $oldTime . "' menjadi '" . $newTime . "'.";
        }

        if (!empty($detailedChanges)) {
            $description .= implode(' ', $detailedChanges);
        } elseif (empty($description)) {
            $description = 'Admin memperbarui detail booking ' . $booking->customer_name . ' (Kode: ' . $booking->queue_number . ').';
        }

       
    }
}