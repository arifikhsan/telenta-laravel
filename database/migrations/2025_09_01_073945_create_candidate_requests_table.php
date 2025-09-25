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
        Schema::create('candidate_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manager_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('position_id')->constrained()->onDelete('cascade');
            $table->string('status');
            $table->integer('requested_count');
            $table->integer('fulfilled_count');
            $table->date('date_requested');
            $table->string('level');
            $table->text('detail');
            $table->string('salary_min');
            $table->string('salary_max');
            $table->string('category');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manager_candidate_requests');
    }
};
