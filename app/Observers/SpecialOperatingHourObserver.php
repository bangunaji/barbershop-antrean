<?php

namespace App\Observers;

use App\Models\SpecialOperatingHour;
use App\Models\ActivityLog;
use Illuminate\Support\Carbon; 

class SpecialOperatingHourObserver
{
    public function created(SpecialOperatingHour $specialOperatingHour): void
    {
        
        $dateFormatted = $specialOperatingHour->date->setTimezone(config('app.timezone'))->format('d M Y');
        $openTime = $specialOperatingHour->open_time ? $specialOperatingHour->open_time->setTimezone(config('app.timezone'))->format('H:i') : '-';
        $closeTime = $specialOperatingHour->close_time ? $specialOperatingHour->close_time->setTimezone(config('app.timezone'))->format('H:i') : '-';

        ActivityLog::createLog(
            'created_special_schedule',
            $specialOperatingHour,
            'Admin menambahkan pengecualian jadwal untuk tanggal ' . $dateFormatted . 
            ' (' . ($specialOperatingHour->is_closed ? 'Tutup' : 'Buka ' . $openTime . '-' . $closeTime) . ').'
        );
    }

    public function updated(SpecialOperatingHour $specialOperatingHour): void
    {
        $changes = $specialOperatingHour->getChanges();
        if (empty($changes)) return;

        $dateFormatted = $specialOperatingHour->date->setTimezone(config('app.timezone'))->format('d M Y');
        $description = 'Admin memperbarui pengecualian jadwal untuk tanggal ' . $dateFormatted . '. ';
        
        if (isset($changes['is_closed'])) {
            $oldStatus = $specialOperatingHour->getOriginal('is_closed') ? 'Tutup' : 'Buka';
            $newStatus = $changes['is_closed'] ? 'Tutup' : 'Buka';
            $description .= "Status berubah dari '{$oldStatus}' menjadi '{$newStatus}'. ";
        }
        if (isset($changes['open_time'])) {
            
            $oldTime = $specialOperatingHour->getOriginal('open_time') ? \Illuminate\Support\Carbon::parse($specialOperatingHour->getOriginal('open_time'))->setTimezone(config('app.timezone'))->format('H:i') : '-';
            $newTime = $changes['open_time'] ? \Illuminate\Support\Carbon::parse($changes['open_time'])->setTimezone(config('app.timezone'))->format('H:i') : '-';
            $description .= "Jam buka berubah dari '{$oldTime}' menjadi '{$newTime}'. ";
        }
        if (isset($changes['close_time'])) {
            
            $oldTime = $specialOperatingHour->getOriginal('close_time') ? \Illuminate\Support\Carbon::parse($specialOperatingHour->getOriginal('close_time'))->setTimezone(config('app.timezone'))->format('H:i') : '-';
            $newTime = $changes['close_time'] ? \Illuminate\Support\Carbon::parse($changes['close_time'])->setTimezone(config('app.timezone'))->format('H:i') : '-';
            $description .= "Jam tutup berubah dari '{$oldTime}' menjadi '{$newTime}'. ";
        }
        if (isset($changes['notes'])) {
            $description .= 'Catatan diubah. ';
        }

        ActivityLog::createLog(
            'updated_special_schedule',
            $specialOperatingHour,
            trim($description),
            array_intersect_key($specialOperatingHour->getOriginal(), $changes),
            $changes
        );
    }

    public function deleted(SpecialOperatingHour $specialOperatingHour): void
    {
        ActivityLog::createLog(
            'deleted_special_schedule',
            $specialOperatingHour,
            'Admin menghapus pengecualian jadwal untuk tanggal ' . $specialOperatingHour->date->format('d M Y') . '.'
        );
    }
}