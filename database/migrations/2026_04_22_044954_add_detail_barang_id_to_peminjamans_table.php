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
    Schema::table('peminjamans', function (Blueprint $table) {
        $table->foreignId('detail_barang_id')
              ->nullable()
              ->constrained('detail_barangs')
              ->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('peminjamans', function (Blueprint $table) {
        $table->dropForeign(['detail_barang_id']);
        $table->dropColumn('detail_barang_id');
    });
}
};
