<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use App\Models\DefaultOperatingHour; 

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $days = [
            0 => ['day_name' => 'Minggu', 'open' => '00:00:00', 'close' => '00:00:00', 'is_closed' => true],
            1 => ['day_name' => 'Senin', 'open' => '09:00:00', 'close' => '17:00:00', 'is_closed' => false],
            2 => ['day_name' => 'Selasa', 'open' => '09:00:00', 'close' => '17:00:00', 'is_closed' => false],
            3 => ['day_name' => 'Rabu', 'open' => '09:00:00', 'close' => '17:00:00', 'is_closed' => false],
            4 => ['day_name' => 'Kamis', 'open' => '09:00:00', 'close' => '17:00:00', 'is_closed' => false],
            5 => ['day_name' => 'Jumat', 'open' => '09:00:00', 'close' => '17:00:00', 'is_closed' => false],
            6 => ['day_name' => 'Sabtu', 'open' => '09:00:00', 'close' => '17:00:00', 'is_closed' => false],
        ];

        foreach ($days as $dayOfWeek => $data) {
            DefaultOperatingHour::create([
                'day_of_week' => $dayOfWeek,
                'open_time' => $data['open'],
                'close_time' => $data['close'],
                'is_closed' => $data['is_closed'],
            ]);
        }
    }
}