<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Service::truncate();

        
        Service::create([
            'name' => 'Potong Rambut Pria',
            'description' => 'Potongan rambut sesuai gaya kekinian atau klasik.',
            'price' => 50000,
            'duration_minutes' => 30,
        ]);

        Service::create([
            'name' => 'Potong Rambut & Cuci',
            'description' => 'Potongan rambut lengkap dengan keramas dan styling.',
            'price' => 75000,
            'duration_minutes' => 45,
        ]);

        Service::create([
            'name' => 'Shaving Janggut',
            'description' => 'Pencukuran janggut bersih dengan handuk hangat.',
            'price' => 40000,
            'duration_minutes' => 20,
        ]);

        Service::create([
            'name' => 'Trim Janggut',
            'description' => 'Perapian janggut dan kumis.',
            'price' => 30000,
            'duration_minutes' => 15,
        ]);

        Service::create([
            'name' => 'Creambath',
            'description' => 'Perawatan rambut dengan cream khusus dan pijatan.',
            'price' => 60000,
            'duration_minutes' => 40,
        ]);

        Service::create([
            'name' => 'Paket Komplit A',
            'description' => 'Potong rambut, cuci, dan shaving janggut.',
            'price' => 120000,
            'duration_minutes' => 70,
        ]);

        Service::create([
            'name' => 'Paket Komplit B',
            'description' => 'Potong rambut, cuci, creambath.',
            'price' => 110000,
            'duration_minutes' => 80,
        ]);
    }
}