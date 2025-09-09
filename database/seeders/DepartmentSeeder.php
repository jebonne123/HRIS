<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Human Resources',
                'code' => 'HR',
            ],
            [
                'name' => 'Information Technology',
                'code' => 'IT',
            ],
            [
                'name' => 'Finance',
                'code' => 'FIN',
            ],
            [
                'name' => 'Operations',
                'code' => 'OPS',
            ],
            [
                'name' => 'Sales',
                'code' => 'SALES',
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}




