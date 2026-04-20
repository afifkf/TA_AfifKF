<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('keuangans', function (Blueprint $table) {
    $table->id();

    $table->foreignId('perawatan_id')
        ->constrained('perawatans')
        ->cascadeOnDelete();

    $table->string('jenis')->default('pengeluaran');
    $table->integer('nominal');
    $table->text('keterangan')->nullable();
    $table->date('tanggal');

    $table->timestamps();
});
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangans');
    }
};
