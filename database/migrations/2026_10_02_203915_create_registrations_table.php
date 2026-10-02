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
    Schema::create('registrations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('activity_id')->constrained()->onDelete('cascade');
        $table->string('user_email');
        $table->timestamps();

        // Mencegah email yang sama mendaftar dua kali di activity yang sama
        $table->unique(['activity_id', 'user_email']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
