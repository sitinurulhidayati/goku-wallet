<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // Tambahkan kolom di bawah ini:
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->string('type'); // Untuk membedakan 'income' atau 'expense'
            $table->bigInteger('amount'); // Nominal uang
            $table->string('desc'); // Keterangan transaksi
            $table->date('date'); // Tanggal transaksi
            $table->timestamps(); // Mengisi otomatis created_at & updated_at
        });
    }

    /**
     * Batalkan migrasi (menghapus tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};