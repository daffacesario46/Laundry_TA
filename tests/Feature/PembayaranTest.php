<?php

namespace Tests\Feature;

use App\Models\Cucian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;  // ← IMPORT INI

class PembayaranTest extends TestCase
{
    use RefreshDatabase;

    protected User $staff;

    protected function setUp(): void
    {
        parent::setUp();
        $this->staff = User::factory()->create(['role' => 'staff']);
    }

    /** @test */
    public function staff_can_view_pembayaran_list()
    {
        Cucian::factory()->create(['status_cucian' => 'selesai']);

        $response = $this->actingAs($this->staff)->get('/staff/pembayaran');

        $response->assertStatus(200);
    }

    /** @test */
    public function staff_can_mark_payment_as_paid()
    {
        $cucian = Cucian::factory()->create([
            'status_cucian' => 'selesai',
        ]);

        $response = $this->actingAs($this->staff)
                        ->post("/staff/cucian/{$cucian->cucian_id}/bayar", [
                            'metode' => 'cash',
                            'jumlah' => 150000
                        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('cucian', [
            'cucian_id' => $cucian->cucian_id,
            'status_cucian' => 'diambil'
        ]);
    }
}
