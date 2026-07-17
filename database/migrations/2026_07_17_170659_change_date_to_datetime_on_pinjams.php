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
    Schema::table('peminjamans', function (Blueprint $table) {

        $table->dateTime('tanggal_pinjam')->change();
        $table->dateTime('batas_kembali')->change();
        $table->dateTime('tanggal_dikembalikan')->nullable()->change();

    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datetime_on_pinjams', function (Blueprint $table) {
            //
        });
    }
};
