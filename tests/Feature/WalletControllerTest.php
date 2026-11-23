<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WalletControllerTest extends TestCase
{
    use RefreshDatabase;

    // ===== TEST PEMASUKAN =====
    public function test_user_dapat_menyimpan_transaksi_pemasukan()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/save', [
            'type'   => 'income',
            'amount' => 100000,
            'desc'   => 'Uang saku',
            'date'   => now()->toDateString(),
        ]);

        $response->assertStatus(302); // redirect
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'type'    => 'income',
            'amount'  => 100000,
        ]);
    }

    // ===== TEST PENGELUARAN =====
    public function test_user_dapat_menyimpan_transaksi_pengeluaran()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/save', [
            'type'   => 'expense',
            'amount' => 50000,
            'desc'   => 'Bayar Listrik',
            'date'   => now()->toDateString(),
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'type'    => 'expense',
            'amount'  => 50000,
        ]);
    }

    // ===== TEST DATA LAPORAN / GRAFIK =====
    public function test_data_laporan_terkirim_ke_view()
    {
        $user = User::factory()->create();

        // Buat beberapa transaksi untuk income & expense
        Transaction::factory()->count(2)->create([
            'user_id' => $user->id, 'type' => 'income', 'amount' => 1000
        ]);
        Transaction::factory()->count(3)->create([
            'user_id' => $user->id, 'type' => 'expense', 'amount' => 500
        ]);

        $this->actingAs($user);

        $response = $this->get('/'); // route dashboard

        $response->assertStatus(200);
        $response->assertViewHas('transactions'); // data transaksi ada
        $response->assertViewHas('totalIncome', 2000); // 2x1000
        $response->assertViewHas('totalExpense', 1500); // 3x500
        $response->assertViewHas('saldo', 500); // totalIncome - totalExpense
    }
}
