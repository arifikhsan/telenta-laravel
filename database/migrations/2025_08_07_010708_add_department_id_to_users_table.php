<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add the department_id column, making sure it can be nullable initially
            $table->unsignedBigInteger('department_id')->nullable();

            // Add the foreign key constraint
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the foreign key and the department_id column
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }
};
