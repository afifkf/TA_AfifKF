<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE peminjamans
            MODIFY COLUMN status ENUM(
                'dipinjam',
                'dikembalikan',
                'terlambat'
            ) NOT NULL DEFAULT 'dipinjam'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE peminjamans
            MODIFY COLUMN status ENUM(
                'dipinjam',
                'dikembalikan'
            ) NOT NULL DEFAULT 'dipinjam'
        ");
    }
};