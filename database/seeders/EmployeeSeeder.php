<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Employee;
use App\Models\Department;
use App\Models\User;
use App\Models\EmploymentStatus;
use App\Models\Shift;
use App\Models\Designation;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure some departments exist
        $deptGeneral = Department::firstOrCreate(['code' => 'GEN'], ['name' => 'General']);
        $deptEng = Department::firstOrCreate(['code' => 'ENG'], ['name' => 'Engineering']);
        $deptHR = Department::firstOrCreate(['code' => 'HR'], ['name' => 'Human Resources']);
        $deptSales = Department::firstOrCreate(['code' => 'SAL'], ['name' => 'Sales']);

        $statusPermanent = EmploymentStatus::firstOrCreate(['name' => 'Permanent']);
        $statusProbation = EmploymentStatus::firstOrCreate(['name' => 'Probation']);
        $statusContract = EmploymentStatus::firstOrCreate(['name' => 'Contract']);
        $statusIntern = EmploymentStatus::firstOrCreate(['name' => 'Intern']);
        $statusTerminated = EmploymentStatus::firstOrCreate(['name' => 'Terminated']);

        // Ensure some common designations
        $desDirector = Designation::firstOrCreate(['name' => 'Director'], ['code' => 'DIR']);
        $desEngineer = Designation::firstOrCreate(['name' => 'Engineer'], ['code' => 'ENGR']);
        $desHR = Designation::firstOrCreate(['name' => 'HR Specialist'], ['code' => 'HRS']);
        $desSales = Designation::firstOrCreate(['name' => 'Sales Associate'], ['code' => 'SALES']);
        $desIntern = Designation::firstOrCreate(['name' => 'Intern'], ['code' => 'INT']);

        $employees = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'full_name' => 'John Doe',
                'department_id' => $deptGeneral->id,
                'designation_id' => $desDirector->id,
                'hire_date' => now()->subYears(2)->toDateString(),
                'status' => 'active',
                'employment_status_id' => $statusPermanent->id,
                'email' => 'john.doe@example.com',
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'full_name' => 'Jane Smith',
                'department_id' => $deptEng->id,
                'designation_id' => $desEngineer->id,
                'hire_date' => now()->subMonths(6)->toDateString(),
                'status' => 'active',
                'employment_status_id' => $statusProbation->id,
                'email' => 'jane.smith@example.com',
            ],
            [
                'first_name' => 'Carlos',
                'last_name' => 'Reyes',
                'full_name' => 'Carlos Reyes',
                'department_id' => $deptSales->id,
                'designation_id' => $desSales->id,
                'hire_date' => now()->subYears(1)->toDateString(),
                'status' => 'active',
                'employment_status_id' => $statusContract->id,
                'email' => 'carlos.reyes@example.com',
            ],
            [
                'first_name' => 'Amira',
                'last_name' => 'Hassan',
                'full_name' => 'Amira Hassan',
                'department_id' => $deptHR->id,
                'designation_id' => $desHR->id,
                'hire_date' => now()->subMonths(3)->toDateString(),
                'status' => 'active',
                'employment_status_id' => $statusIntern->id,
                'email' => 'amira.hassan@example.com',
            ],
            [
                'first_name' => 'Liam',
                'last_name' => 'Nguyen',
                'full_name' => 'Liam Nguyen',
                'department_id' => $deptEng->id,
                'designation_id' => $desEngineer->id,
                'hire_date' => now()->subYears(3)->toDateString(),
                'status' => 'active',
                'employment_status_id' => $statusPermanent->id,
                'email' => 'liam.nguyen@example.com',
            ],
            [
                'first_name' => 'Sophia',
                'last_name' => 'Garcia',
                'full_name' => 'Sophia Garcia',
                'department_id' => $deptSales->id,
                'designation_id' => $desSales->id,
                'hire_date' => now()->subMonths(8)->toDateString(),
                'status' => 'inactive',
                'employment_status_id' => $statusTerminated->id,
                'email' => 'sophia.garcia@example.com',
            ],
            [
                'first_name' => 'Noah',
                'last_name' => 'Khan',
                'full_name' => 'Noah Khan',
                'department_id' => $deptGeneral->id,
                'designation_id' => $desEngineer->id,
                'hire_date' => now()->subMonths(10)->toDateString(),
                'status' => 'active',
                'employment_status_id' => $statusProbation->id,
                'email' => 'noah.khan@example.com',
            ],
            [
                'first_name' => 'Emily',
                'last_name' => 'Brown',
                'full_name' => 'Emily Brown',
                'department_id' => $deptHR->id,
                'designation_id' => $desHR->id,
                'hire_date' => now()->subYears(4)->toDateString(),
                'status' => 'active',
                'employment_status_id' => $statusPermanent->id,
                'email' => 'emily.brown@example.com',
            ],
        ];

        // Ensure a default shift exists
        $defaultShift = Shift::firstOrCreate(
            ['name' => 'Default Day Shift'],
            [
                'start_time_1' => '09:00:00',
                'end_time_1' => '18:00:00',
            ]
        );

        foreach ($employees as $item) {
            $employee = Employee::firstOrCreate(
                [
                    'full_name' => $item['full_name'],
                    'department_id' => $item['department_id'],
                ],
                [
                'first_name' => $item['first_name'],
                'last_name' => $item['last_name'],
                'hire_date' => $item['hire_date'],
                'status' => $item['status'],
                'employment_status_id' => $item['employment_status_id'],
                'designation_id' => $item['designation_id'] ?? null,
                ]
            );

            $user = User::where('email', $item['email'])->first();
            if (!$user) {
                $user = User::create([
                    'name' => $employee->full_name,
                    'email' => $item['email'],
                    'password' => Hash::make('password'),
                    'role' => 'employee',
                    'employee_id' => $employee->id,
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]);
            } else {
                // Ensure the existing user is linked to the employee
                if (!$user->employee_id) {
                    $user->employee_id = $employee->id;
                    $user->save();
                }
            }

            // Attach default shift if none assigned
            if ($employee->shifts()->count() === 0) {
                $employee->shifts()->attach($defaultShift->id, [
                    'effective_from' => now()->subYear(),
                    'effective_until' => null,
                ]);
            }

            // Ensure primary_shift_id is set
            if (!$employee->primary_shift_id) {
                $employee->primary_shift_id = $defaultShift->id;
                $employee->save();
            }
        }
    }
}


