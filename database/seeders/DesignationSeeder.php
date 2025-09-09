<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesignationSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $rows = [
            ['name' => 'Director', 'code' => 'DIR'],
            ['name' => 'Engineer', 'code' => 'ENGR'],
            ['name' => 'HR Specialist', 'code' => 'HRS'],
            ['name' => 'Sales Associate', 'code' => 'SALES'],
            ['name' => 'Intern', 'code' => 'INT'],
        ];

        foreach ($rows as $row) {
            DB::table('designations')->updateOrInsert(
                ['name' => $row['name']],
                ['name' => $row['name'], 'code' => $row['code'], 'created_at' => $now, 'updated_at' => $now]
            );
        }
    }
}



