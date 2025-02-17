<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('App Name')->nullable();
            $table->string('logo')->default('Logo')->nullable();
            $table->string('favicon')->default('Favicon')->nullable();
            $table->string('address')->default('Address')->nullable();

            $table->string('phone')->default('+966 50 000 0000')->nullable();
            $table->string('email')->default('Rg6ZM@example.com')->nullable();

            $table->float('commission')->default(0)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
