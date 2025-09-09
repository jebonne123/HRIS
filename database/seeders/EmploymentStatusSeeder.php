<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmploymentStatusSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $rows = [
            ['name' => 'Permanent', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Probation', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Contract', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Intern', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Terminated', 'created_at' => $now, 'updated_at' => $now],
        ];

        foreach ($rows as $row) {
            DB::table('employment_statuses')->updateOrInsert(['name' => $row['name']], $row);
        }
    }
}


