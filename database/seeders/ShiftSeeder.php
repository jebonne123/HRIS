<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shift;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        Shift::create([
            'name' => 'Default Day Shift',
            'start_time_1' => '08:00:00',
            'end_time_1' => '12:00:00',
            'start_time_2' => '13:00:00',
            'end_time_2' => '17:00:00',
            'bell_enabled' => false,
            'allow_late_minutes' => 0,
            'allow_early_minutes' => 0,
            'rest_days' => 2,
            'enable_ot' => true,
            'ot_start_minutes' => 60,
            'ot_valid_minutes' => 30,
        ]);

        Shift::create([
            'name' => 'Night Shift',
            'start_time_1' => '20:00:00',
            'end_time_1' => '00:00:00',
            'start_time_2' => '00:00:00',
            'end_time_2' => '04:00:00',
            'bell_enabled' => false,
            'allow_late_minutes' => 15,
            'allow_early_minutes' => 15,
            'rest_days' => 2,
            'enable_ot' => true,
            'ot_start_minutes' => 60,
            'ot_valid_minutes' => 30,
        ]);
    }
}




