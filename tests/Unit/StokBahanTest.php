<?php

namespace Tests\Unit;

use App\Models\StokBahan;
use App\Models\Pemakaian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StokBahanTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'jenis_bahan',
            'merk',
            'stok_tersedia',
            'satuan',
            'harga_beli',
            'stok_minimum',
            'deskripsi'
        ];

        $stokBahan = new StokBahan();
        $this->assertEquals($fillable, $stokBahan->getFillable());
    }

    /** @test */
    public function it_has_many_pemakaian()
    {
        $stokBahan = StokBahan::factory()->create();
        Pemakaian::factory()->create(['stok_bahan_id' => $stokBahan->stok_bahan_id]);

        $this->assertInstanceOf(Pemakaian::class, $stokBahan->pemakaian->first());
        $this->assertEquals(1, $stokBahan->pemakaian->count());
    }

    /** @test */
    public function is_stok_rendah_returns_true_when_below_minimum()
    {
        $stokBahan = StokBahan::factory()->create([
            'stok_tersedia' => 5,
            'stok_minimum' => 10
        ]);

        $this->assertTrue($stokBahan->isStokRendah());
    }

    /** @test */
    public function is_stok_rendah_returns_false_when_above_minimum()
    {
        $stokBahan = StokBahan::factory()->create([
            'stok_tersedia' => 15,
            'stok_minimum' => 10
        ]);

        $this->assertFalse($stokBahan->isStokRendah());
    }

    /** @test */
    public function is_stok_rendah_returns_false_when_minimum_is_null()
    {
        $stokBahan = StokBahan::factory()->create([
            'stok_tersedia' => 5,
            'stok_minimum' => null
        ]);

        $this->assertFalse($stokBahan->isStokRendah());
    }

    /** @test */
    public function is_stok_habis_returns_true_when_zero()
    {
        $stokBahan = StokBahan::factory()->create(['stok_tersedia' => 0]);

        $this->assertTrue($stokBahan->isStokHabis());
    }

    /** @test */
    public function is_stok_habis_returns_true_when_negative()
    {
        $stokBahan = StokBahan::factory()->create(['stok_tersedia' => -1]);

        $this->assertTrue($stokBahan->isStokHabis());
    }

    /** @test */
    public function is_stok_habis_returns_false_when_positive()
    {
        $stokBahan = StokBahan::factory()->create(['stok_tersedia' => 5]);

        $this->assertFalse($stokBahan->isStokHabis());
    }

    /** @test */
    public function get_stok_badge_returns_danger_when_habis()
    {
        $stokBahan = StokBahan::factory()->create(['stok_tersedia' => 0]);

        $this->assertEquals('alert-danger', $stokBahan->getStokBadge());
    }

    /** @test */
    public function get_stok_badge_returns_warning_when_rendah()
    {
        $stokBahan = StokBahan::factory()->create([
            'stok_tersedia' => 5,
            'stok_minimum' => 10
        ]);

        $this->assertEquals('alert-warning', $stokBahan->getStokBadge());
    }

    /** @test */
    public function get_stok_badge_returns_success_when_aman()
    {
        $stokBahan = StokBahan::factory()->create([
            'stok_tersedia' => 20,
            'stok_minimum' => 10
        ]);

        $this->assertEquals('alert-success', $stokBahan->getStokBadge());
    }

    /** @test */
    public function get_stok_label_returns_correct_labels()
    {
        $stokHabis = StokBahan::factory()->create(['stok_tersedia' => 0]);
        $this->assertEquals('Stok Habis', $stokHabis->getStokLabel());

        $stokRendah = StokBahan::factory()->create([
            'stok_tersedia' => 5,
            'stok_minimum' => 10
        ]);
        $this->assertEquals('Stok Rendah', $stokRendah->getStokLabel());

        $stokAman = StokBahan::factory()->create([
            'stok_tersedia' => 20,
            'stok_minimum' => 10
        ]);
        $this->assertEquals('Stok Aman', $stokAman->getStokLabel());
    }

    /** @test */
    public function get_formatted_stok_returns_formatted_value()
    {
        $stokBahan = StokBahan::factory()->create([
            'stok_tersedia' => 25.5,
            'satuan' => 'kg'
        ]);

        $this->assertEquals('25.50 kg', $stokBahan->getFormattedStok());
    }

    /** @test */
    public function get_formatted_harga_beli_returns_formatted_currency()
    {
        $stokBahan = StokBahan::factory()->create(['harga_beli' => 50000]);

        $this->assertEquals('Rp 50.000', $stokBahan->getFormattedHargaBeli());
    }

    /** @test */
    public function get_nilai_stok_calculates_correctly()
    {
        $stokBahan = StokBahan::factory()->create([
            'stok_tersedia' => 10,
            'harga_beli' => 5000
        ]);

        $this->assertEquals(50000, $stokBahan->getNilaiStok());
    }

    /** @test */
    public function get_formatted_nilai_stok_returns_formatted_currency()
    {
        $stokBahan = StokBahan::factory()->create([
            'stok_tersedia' => 10,
            'harga_beli' => 5000
        ]);

        $this->assertEquals('Rp 50.000', $stokBahan->getFormattedNilaiStok());
    }

    /** @test */
    public function it_casts_numeric_fields_to_double()
    {
        $stokBahan = StokBahan::factory()->create([
            'stok_tersedia' => '100',
            'harga_beli' => '50000',
            'stok_minimum' => '10'
        ]);

        $this->assertIsFloat($stokBahan->stok_tersedia);
        $this->assertIsFloat($stokBahan->harga_beli);
        $this->assertIsFloat($stokBahan->stok_minimum);
    }
}
