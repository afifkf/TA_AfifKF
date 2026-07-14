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

    $table->foreignId('admin_id')
          ->nullable()
          ->after('user_id')
          ->constrained('users')
          ->nullOnDelete();

    $table->timestamp('tanggal_disetujui')->nullable();

    $table->text('alasan_penolakan')->nullable();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
