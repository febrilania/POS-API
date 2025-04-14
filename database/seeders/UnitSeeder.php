<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = ['pcs', 'dus', 'kg', 'liter', 'pack' ];
        foreach($units as $unit){
            Unit::create(['name' => $unit]);
        }
    }
}
