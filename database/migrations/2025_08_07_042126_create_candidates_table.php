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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('position_id')->constrained()->onDelete('cascade');
            $table->foreignId('manager_id')->constrained('users')->onDelete('cascade');
            $table->string('status');
            $table->integer('days_required')->nullable(); // Make this column nullable
            $table->date('proposed_date')->nullable(); // Make this column nullable
            $table->date('cv_review_date')->nullable(); // Make this column nullable
            $table->date('hr_interview_date')->nullable(); // Make this column nullable
            $table->string('cv_path')->nullable(); // Make this column nullable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
