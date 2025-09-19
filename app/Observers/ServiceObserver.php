<?php

namespace App\Observers;

use App\Models\Service;
use Illuminate\Support\Carbon; 

class ServiceObserver
{
    public function created(Service $service): void
    {
       
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

       
    }

    public function deleted(Service $service): void
    {
       
    }
}