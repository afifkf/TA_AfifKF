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
        Schema::create('perawatans', function (Blueprint $table) {
    $table->id();

    $table->foreignId('barang_rusak_id')
    ->nullable()
    ->constrained('barang_rusaks')
    ->nullOnDelete();

    $table->string('nama_barang');
    $table->date('tanggal');
    $table->integer('biaya')->default(0);
    $table->string('status')->default('proses');
    $table->text('keterangan')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perawatans');
    }
};
