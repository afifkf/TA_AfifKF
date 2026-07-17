<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_barang_pinjam', function (Blueprint $table) {

            $table->id();

            $table->foreignId('pinjam_id')
                ->constrained('peminjamans')
                ->cascadeOnDelete();

            $table->foreignId('detail_barang_id')
                ->constrained('detail_barangs')
                ->cascadeOnDelete();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_barang_pinjam');
    }
};