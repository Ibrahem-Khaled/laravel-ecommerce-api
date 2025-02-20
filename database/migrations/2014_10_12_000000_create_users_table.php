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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique();
            $table->string('address')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'inactive', 'deleted', 'blocked'])->default('active');
            $table->enum('role', ['admin', 'user', 'worker', 'delivery'])->default('user');
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'Enad.codex@gmail.com',
            'phone' => '01000000000',
            'password' => Hash::make('enad2011'),
            'role' => 'admin',
            'status' => 'active',
            'gender' => 'male',
            'image' => 'https://images.unsplash.com/photo-1499714608240-22fc6ad53fb2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=870&q=80'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
