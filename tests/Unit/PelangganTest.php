<?php

namespace Tests\Unit;

use App\Models\Pelanggan;
use App\Models\User;
use App\Models\Cucian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PelangganTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'users_id',
            'kategori_pelanggan',
            'nama',
            'no_telp',
            'no_wa',
            'alamat',
            'foto',
            'status'
        ];

        $pelanggan = new Pelanggan();
        $this->assertEquals($fillable, $pelanggan->getFillable());
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $pelanggan = Pelanggan::factory()->create();
        
        $this->assertInstanceOf(User::class, $pelanggan->user);
    }

    /** @test */
    public function it_has_many_cucian()
    {
        $pelanggan = Pelanggan::factory()->create();
        $cucian = Cucian::factory()->create(['pelanggan_id' => $pelanggan->pelanggan_id]);

        $this->assertInstanceOf(Cucian::class, $pelanggan->cucian->first());
        $this->assertEquals(1, $pelanggan->cucian->count());
    }

    /** @test */
    public function is_member_returns_true_for_member()
    {
        $pelanggan = Pelanggan::factory()->create([
            'kategori_pelanggan' => 'member'
        ]);

        $this->assertTrue($pelanggan->isMember());
    }

    /** @test */
    public function is_member_returns_false_for_non_member()
    {
        $pelanggan = Pelanggan::factory()->create([
            'kategori_pelanggan' => 'umum'
        ]);

        $this->assertFalse($pelanggan->isMember());
    }

    /** @test */
    public function is_aktif_returns_true_for_active_pelanggan()
    {
        $pelanggan = Pelanggan::factory()->create([
            'status' => 'aktif'
        ]);

        $this->assertTrue($pelanggan->isAktif());
    }

    /** @test */
    public function is_aktif_returns_false_for_inactive_pelanggan()
    {
        $pelanggan = Pelanggan::factory()->create([
            'status' => 'nonaktif'
        ]);

        $this->assertFalse($pelanggan->isAktif());
    }

    /** @test */
    public function get_total_order_returns_correct_count()
    {
        $pelanggan = Pelanggan::factory()->create();
        Cucian::factory()->count(3)->create(['pelanggan_id' => $pelanggan->pelanggan_id]);

        $this->assertEquals(3, $pelanggan->getTotalOrder());
    }

    /** @test */
    public function get_total_spending_returns_correct_sum()
    {
        $pelanggan = Pelanggan::factory()->create();
        Cucian::factory()->create(['pelanggan_id' => $pelanggan->pelanggan_id, 'total_harga' => 50000]);
        Cucian::factory()->create(['pelanggan_id' => $pelanggan->pelanggan_id, 'total_harga' => 75000]);

        $this->assertEquals(125000, $pelanggan->getTotalSpending());
    }

    /** @test */
    public function get_kategori_label_returns_member_for_member()
    {
        $pelanggan = Pelanggan::factory()->create(['kategori_pelanggan' => 'member']);
        
        $this->assertEquals('Member', $pelanggan->getKategoriLabel());
    }

    /** @test */
    public function get_kategori_label_returns_umum_for_non_member()
    {
        $pelanggan = Pelanggan::factory()->create(['kategori_pelanggan' => 'umum']);
        
        $this->assertEquals('Umum', $pelanggan->getKategoriLabel());
    }

    /** @test */
    public function get_kategori_badge_returns_success_for_member()
    {
        $pelanggan = Pelanggan::factory()->create(['kategori_pelanggan' => 'member']);
        
        $this->assertEquals('badge bg-success', $pelanggan->getKategoriBadge());
    }

    /** @test */
    public function get_kategori_badge_returns_secondary_for_non_member()
    {
        $pelanggan = Pelanggan::factory()->create(['kategori_pelanggan' => 'umum']);
        
        $this->assertEquals('badge bg-secondary', $pelanggan->getKategoriBadge());
    }

    /** @test */
    public function get_status_badge_returns_success_for_active()
    {
        $pelanggan = Pelanggan::factory()->create(['status' => 'aktif']);
        
        $this->assertEquals('badge bg-success', $pelanggan->getStatusBadge());
    }

    /** @test */
    public function get_status_badge_returns_warning_for_inactive()
    {
        $pelanggan = Pelanggan::factory()->create(['status' => 'nonaktif']);
        
        $this->assertEquals('badge bg-warning', $pelanggan->getStatusBadge());
    }

    /** @test */
    public function has_foto_returns_false_when_no_foto()
    {
        $pelanggan = Pelanggan::factory()->create(['foto' => null]);
        
        $this->assertFalse($pelanggan->hasFoto());
    }
}
