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
            // Add the client_id column, making sure it can be nullable initially
            $table->unsignedBigInteger('client_id')->nullable();

            // Add the foreign key constraint
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the foreign key and the client_id column
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
        });
    }
};
