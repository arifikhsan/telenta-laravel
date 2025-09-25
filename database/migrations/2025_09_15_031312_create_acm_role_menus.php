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
        Schema::create('acm_role_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('acm_menu_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('create');
            $table->tinyInteger('read');
            $table->tinyInteger('update');
            $table->tinyInteger('delete');
            $table->tinyInteger('export');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acm_role_menus');
    }
};
