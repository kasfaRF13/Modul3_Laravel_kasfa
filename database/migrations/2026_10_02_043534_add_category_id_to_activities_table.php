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
        Schema::table('activities', function (Blueprint $table) {
            // Menambahkan foreign key category_id yang terhubung ke tabel categories
            // restrictOnDelete() memastikan kategori yang masih dipakai kegiatan TIDAK BISA dihapus
            $table->foreignId('category_id')
                  ->after('id')
                  ->constrained('categories')
                  ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
