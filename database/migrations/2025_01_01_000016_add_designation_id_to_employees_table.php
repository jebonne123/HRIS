<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('designation_id')->nullable()->after('department_id');
            $table->foreign('designation_id')->references('id')->on('designations')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            try { $table->dropForeign(['designation_id']); } catch (\Exception $e) {}
            $table->dropColumn('designation_id');
        });
    }
};



