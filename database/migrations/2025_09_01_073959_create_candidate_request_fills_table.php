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
        Schema::create('candidate_request_fills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_request_id')->constrained('candidate_requests')->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained('candidates')->onDelete('cascade');
            $table->date('date_filled');
            $table->timestamp('interview_manager')->nullable();
            $table->timestamp('interview_client')->nullable();
            $table->timestamp('interview_hr')->nullable();
            $table->string('sla')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_request_fills');
    }
};
