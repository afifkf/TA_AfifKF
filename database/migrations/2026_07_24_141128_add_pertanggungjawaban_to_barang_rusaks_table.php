<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang_rusaks', function (Blueprint $table) {

            $table->enum('jenis_pertanggungjawaban', [
                'ganti_barang',
                'ganti_uang'
            ])
            ->nullable()
            ->after('status');

            $table->enum('status_pertanggungjawaban', [
                'menunggu',
                'proses',
                'selesai'
            ])
            ->default('menunggu')
            ->after('jenis_pertanggungjawaban');

            $table->decimal('nominal_ganti', 15, 2)
                ->nullable()
                ->after('status_pertanggungjawaban');

            $table->text('keterangan_pertanggungjawaban')
                ->nullable()
                ->after('nominal_ganti');

        });
    }

    public function down(): void
    {
        Schema::table('barang_rusaks', function (Blueprint $table) {

            $table->dropColumn([
                'jenis_pertanggungjawaban',
                'status_pertanggungjawaban',
                'nominal_ganti',
                'keterangan_pertanggungjawaban'
            ]);

        });
    }
};
