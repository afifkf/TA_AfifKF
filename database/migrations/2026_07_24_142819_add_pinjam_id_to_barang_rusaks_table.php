<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang_rusaks', function (Blueprint $table) {

            $table->foreignId('pinjam_id')
                ->nullable()
                ->constrained('peminjamans')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('barang_rusaks', function (Blueprint $table) {

            $table->dropForeign(['pinjam_id']);
            $table->dropColumn('pinjam_id');

        });
    }
};