<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure statuses exist before backfill
        if (!Schema::hasTable('employment_statuses')) {
            Schema::create('employment_statuses', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->timestamps();
            });
        }

        $now = now();
        $defaults = ['Permanent', 'Probation', 'Terminated'];
        foreach ($defaults as $name) {
            DB::table('employment_statuses')->updateOrInsert(
                ['name' => $name],
                ['name' => $name, 'created_at' => $now, 'updated_at' => $now]
            );
        }

        // Backfill employees.employment_status_id using employees.employment_status string
        $statusMap = DB::table('employment_statuses')->pluck('id', 'name');
        $employees = DB::table('employees')->select('id', 'employment_status')->get();
        foreach ($employees as $employee) {
            $name = $employee->employment_status ?: 'Permanent';
            $statusId = $statusMap[$name] ?? $statusMap['Permanent'] ?? null;
            DB::table('employees')->where('id', $employee->id)->update([
                'employment_status_id' => $statusId,
            ]);
        }

        // Add FK and make non-nullable with default to Permanent
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'employment_status_id')) {
                $table->unsignedBigInteger('employment_status_id')->nullable()->after('status');
            }
        });

        // Add the foreign key constraint
        Schema::table('employees', function (Blueprint $table) {
            if (!app()->runningUnitTests()) {
                $table->foreign('employment_status_id')->references('id')->on('employment_statuses')->cascadeOnUpdate();
            }
        });

        // Make column not nullable after backfill
        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('employment_status_id')->nullable(false)->change();
        });

        // Drop old string column if exists
        if (Schema::hasColumn('employees', 'employment_status')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('employment_status');
            });
        }
    }

    public function down(): void
    {
        // Recreate old string column (nullable) and copy back names
        if (!Schema::hasColumn('employees', 'employment_status')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->string('employment_status')->nullable()->after('status');
            });
        }

        // Backfill string from FK
        $map = DB::table('employment_statuses')->pluck('name', 'id');
        $employees = DB::table('employees')->select('id', 'employment_status_id')->get();
        foreach ($employees as $employee) {
            $name = $map[$employee->employment_status_id] ?? null;
            DB::table('employees')->where('id', $employee->id)->update([
                'employment_status' => $name,
            ]);
        }

        // Drop FK and column
        Schema::table('employees', function (Blueprint $table) {
            try { $table->dropForeign(['employment_status_id']); } catch (Exception $e) {}
            $table->dropColumn('employment_status_id');
        });
    }
};



