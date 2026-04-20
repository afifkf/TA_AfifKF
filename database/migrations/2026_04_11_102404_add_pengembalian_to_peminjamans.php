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
        if (!Schema::hasColumn('peminjamans', 'batas_kembali')) {
            $table->date('batas_kembali')->nullable();
        }
    });
}

public function down()
{
    Schema::table('peminjamans', function (Blueprint $table) {
        $table->dropColumn(['batas_kembali','tanggal_dikembalikan']);
    });
}
};
