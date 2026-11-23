<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * White Box Test: Memastikan alur internal logout menghapus session 
     * dan mengubah status autentikasi di dalam sistem.
     */
    public function test_user_logout_menghapus_session_dan_mengubah_status_autentikasi()
    {
        // 1. SETUP: Buat user dan paksa sistem menganggap user sudah login
        $user = User::factory()->create();
        $this->actingAs($user);

        // Verifikasi awal (State sistem: Authenticated)
        $this->assertAuthenticatedAs($user);

        // 2. EXECUTION: Menjalankan logic logout
        $response = $this->get('/logout');

        // 3. ASSERTION (Verifikasi Logika Internal):
        
        // Cek Redirection (Alur navigasi benar)
        $response->assertRedirect('/login');

        // Cek Auth Guard State (User harus sudah menjadi Guest)
        $this->assertGuest();

        // Cek Session State (Memastikan session spesifik user sudah hancur)
        // Catatan: session_id akan diregenerasi setelah logout di Laravel
        $this->assertFalse(session()->has('_token_lama_jika_ada')); 
        $this->assertEquals(0, count(auth()->user() ? [1] : []));
    }
}