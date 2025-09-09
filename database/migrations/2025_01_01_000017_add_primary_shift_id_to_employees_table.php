<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('primary_shift_id')->nullable()->after('designation_id');
        });

        // Backfill using current assignment in employee_shifts if available
        if (Schema::hasTable('employee_shifts')) {
            $rows = DB::table('employee_shifts')
                ->select('employee_id', DB::raw('MAX(shift_id) as shift_id'))
                ->groupBy('employee_id')
                ->get();
            foreach ($rows as $row) {
                DB::table('employees')->where('id', $row->employee_id)->update(['primary_shift_id' => $row->shift_id]);
            }
        }

        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('primary_shift_id')->references('id')->on('shifts')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            try { $table->dropForeign(['primary_shift_id']); } catch (\Exception $e) {}
            $table->dropColumn('primary_shift_id');
        });
    }
};



