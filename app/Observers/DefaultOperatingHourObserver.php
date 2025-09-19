<?php

namespace App\Observers;

use App\Models\DefaultOperatingHour;
use Illuminate\Support\Carbon; 

class DefaultOperatingHourObserver
{
    private function getDayName(int $dayOfWeek): string
    {
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        return $days[$dayOfWeek] ?? 'Tidak Diketahui';
    }

    public function created(DefaultOperatingHour $defaultOperatingHour): void
    {
        $openTime = $defaultOperatingHour->open_time ? $defaultOperatingHour->open_time->setTimezone(config('app.timezone'))->format('H:i') : '-';
        $closeTime = $defaultOperatingHour->close_time ? $defaultOperatingHour->close_time->setTimezone(config('app.timezone'))->format('H:i') : '-';

    }

    public function updated(DefaultOperatingHour $defaultOperatingHour): void
    {
        $changes = $defaultOperatingHour->getChanges();
        if (empty($changes)) return;

        $description = 'Admin memperbarui jadwal default hari ' . $this->getDayName($defaultOperatingHour->day_of_week) . '. ';
        
        if (isset($changes['is_closed'])) {
            $oldStatus = $defaultOperatingHour->getOriginal('is_closed') ? 'Tutup' : 'Buka';
            $newStatus = $changes['is_closed'] ? 'Tutup' : 'Buka';
            $description .= "Status berubah dari '{$oldStatus}' menjadi '{$newStatus}'. ";
        }
        if (isset($changes['open_time'])) {
            
            $oldTime = $defaultOperatingHour->getOriginal('open_time') ? \Illuminate\Support\Carbon::parse($defaultOperatingHour->getOriginal('open_time'))->setTimezone(config('app.timezone'))->format('H:i') : '-';
            $newTime = $changes['open_time'] ? \Illuminate\Support\Carbon::parse($changes['open_time'])->setTimezone(config('app.timezone'))->format('H:i') : '-';
            $description .= "Jam buka berubah dari '{$oldTime}' menjadi '{$newTime}'. ";
        }
        if (isset($changes['close_time'])) {
            
            $oldTime = $defaultOperatingHour->getOriginal('close_time') ? \Illuminate\Support\Carbon::parse($defaultOperatingHour->getOriginal('close_time'))->setTimezone(config('app.timezone'))->format('H:i') : '-';
            $newTime = $changes['close_time'] ? \Illuminate\Support\Carbon::parse($changes['close_time'])->setTimezone(config('app.timezone'))->format('H:i') : '-';
            $description .= "Jam tutup berubah dari '{$oldTime}' menjadi '{$newTime}'. ";
        }

    }

    public function deleted(DefaultOperatingHour $defaultOperatingHour): void
    {
        
    }
}