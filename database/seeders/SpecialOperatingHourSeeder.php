<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SpecialOperatingHour;
use Carbon\Carbon; 

class SpecialOperatingHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        SpecialOperatingHour::truncate();

        
        SpecialOperatingHour::create([
            'date' => Carbon::now()->addDays(7)->toDateString(), 
            'open_time' => '10:00:00',
            'close_time' => '17:00:00',
            'is_closed' => false,
            'notes' => 'Jam operasional lebih singkat karena ada acara.',
        ]);

        SpecialOperatingHour::create([
            'date' => Carbon::now()->addDays(14)->toDateString(), 
            'open_time' => null, 
            'close_time' => null, 
            'is_closed' => true,
            'notes' => 'Libur Hari Raya Idul Adha.',
        ]);

        SpecialOperatingHour::create([
            'date' => Carbon::now()->addDays(21)->toDateString(), 
            'open_time' => '09:00:00',
            'close_time' => '22:00:00',
            'is_closed' => false,
            'notes' => 'Buka lebih malam untuk promo akhir bulan.',
        ]);
    }
}