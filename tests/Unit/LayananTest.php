<?php

namespace Tests\Unit;

use App\Models\Layanan;
use App\Models\Cucian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayananTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'nama_layanan',
            'jenis_cucian',
            'deskripsi',
            'durasi_hari'
        ];

        $layanan = new Layanan();
        $this->assertEquals($fillable, $layanan->getFillable());
    }

    /** @test */
    public function it_has_many_cucian()
    {
        $layanan = Layanan::factory()->create();
        Cucian::factory()->create(['layanan_id' => $layanan->layanan_id]);

        $this->assertInstanceOf(Cucian::class, $layanan->cucian->first());
        $this->assertEquals(1, $layanan->cucian->count());
    }

    /** @test */
    public function is_kiloan_returns_true_for_kiloan()
    {
        $layanan = Layanan::factory()->create(['jenis_cucian' => 'kiloan']);
        
        $this->assertTrue($layanan->isKiloan());
    }

    /** @test */
    public function is_kiloan_returns_false_for_satuan()
    {
        $layanan = Layanan::factory()->create(['jenis_cucian' => 'satuan']);
        
        $this->assertFalse($layanan->isKiloan());
    }

    /** @test */
    public function is_satuan_returns_true_for_satuan()
    {
        $layanan = Layanan::factory()->create(['jenis_cucian' => 'satuan']);
        
        $this->assertTrue($layanan->isSatuan());
    }

    /** @test */
    public function is_satuan_returns_false_for_kiloan()
    {
        $layanan = Layanan::factory()->create(['jenis_cucian' => 'kiloan']);
        
        $this->assertFalse($layanan->isSatuan());
    }

    /** @test */
    public function it_uses_correct_table_name()
    {
        $layanan = new Layanan();
        
        $this->assertEquals('layanan', $layanan->getTable());
    }

    /** @test */
    public function it_uses_correct_primary_key()
    {
        $layanan = new Layanan();
        
        $this->assertEquals('layanan_id', $layanan->getKeyName());
    }

    /** @test */
    public function it_has_timestamps()
    {
        $layanan = new Layanan();
        
        $this->assertTrue($layanan->usesTimestamps());
    }
}
