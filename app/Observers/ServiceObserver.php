<?php

namespace App\Observers;

use App\Models\Service;
use App\Models\ActivityLog;
use Illuminate\Support\Carbon; 

class ServiceObserver
{
    public function created(Service $service): void
    {
        ActivityLog::createLog(
            'created_service',
            $service,
            'Admin menambahkan layanan baru: ' . $service->name . ' dengan harga Rp' . number_format($service->price, 0, ',', '.') . ' dan durasi ' . $service->duration_minutes . ' menit.'
        );
    }

    public function updated(Service $service): void
    {
        $changes = $service->getChanges();
        if (empty($changes)) return;

        $description = 'Admin memperbarui layanan "' . $service->name . '". ';
        if (isset($changes['price'])) {
            $description .= 'Harga berubah dari Rp' . number_format($service->getOriginal('price'), 0, ',', '.') . ' menjadi Rp' . number_format($changes['price'], 0, ',', '.') . '. ';
        }
        if (isset($changes['duration_minutes'])) {
            $description .= 'Durasi berubah dari ' . $service->getOriginal('duration_minutes') . ' menit menjadi ' . $changes['duration_minutes'] . ' menit. ';
        }
        if (isset($changes['name'])) {
            $description .= 'Nama layanan diubah menjadi "' . $changes['name'] . '". ';
        }

        ActivityLog::createLog(
            'updated_service',
            $service,
            trim($description),
            array_intersect_key($service->getOriginal(), $changes),
            $changes
        );
    }

    public function deleted(Service $service): void
    {
        ActivityLog::createLog(
            'deleted_service',
            $service,
            'Admin menghapus layanan: ' . $service->name . '.'
        );
    }
}