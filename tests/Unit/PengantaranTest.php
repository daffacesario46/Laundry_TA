<?php

namespace Tests\Unit;

use App\Models\Pengantaran;
use App\Models\Cucian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengantaranTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'cucian_id',
            'kurir_id',
            'alamat_antar',
            'status',
            'tgl_berangkat',
            'foto',
            'catatan'
        ];

        $pengantaran = new Pengantaran();
        $this->assertEquals($fillable, $pengantaran->getFillable());
    }

    /** @test */
    public function it_belongs_to_cucian()
    {
        $pengantaran = Pengantaran::factory()->create();
        
        $this->assertInstanceOf(Cucian::class, $pengantaran->cucian);
    }

    /** @test */
    public function it_belongs_to_kurir()
    {
        $pengantaran = Pengantaran::factory()->create();
        
        $this->assertInstanceOf(User::class, $pengantaran->kurir);
    }

    /** @test */
    public function is_menunggu_returns_true_for_menunggu_status()
    {
        $pengantaran = Pengantaran::factory()->create(['status' => 'menunggu']);
        
        $this->assertTrue($pengantaran->isMenunggu());
    }

    /** @test */
    public function is_menunggu_returns_false_for_other_status()
    {
        $pengantaran = Pengantaran::factory()->create(['status' => 'selesai']);
        
        $this->assertFalse($pengantaran->isMenunggu());
    }

    /** @test */
    public function it_uses_correct_table_name()
    {
        $pengantaran = new Pengantaran();
        
        $this->assertEquals('pengantaran', $pengantaran->getTable());
    }

    /** @test */
    public function it_uses_correct_primary_key()
    {
        $pengantaran = new Pengantaran();
        
        $this->assertEquals('pengantaran_id', $pengantaran->getKeyName());
    }

    /** @test */
    public function it_casts_tgl_berangkat_to_datetime()
    {
        $pengantaran = Pengantaran::factory()->create([
            'tgl_berangkat' => '2026-02-09 10:00:00'
        ]);
        
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $pengantaran->tgl_berangkat);
    }
}
