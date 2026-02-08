<?php

namespace Tests\Unit;

use App\Models\Penjemputan;
use App\Models\Cucian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenjemputanTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'cucian_id',
            'staff_id',
            'alamat_jemput',
            'status',
            'tgl_order',
            'foto',
            'catatan'
        ];

        $penjemputan = new Penjemputan();
        $this->assertEquals($fillable, $penjemputan->getFillable());
    }

    /** @test */
    public function it_belongs_to_cucian()
    {
        $penjemputan = Penjemputan::factory()->create();
        
        $this->assertInstanceOf(Cucian::class, $penjemputan->cucian);
    }

    /** @test */
    public function it_belongs_to_staff()
    {
        $penjemputan = Penjemputan::factory()->create();
        
        $this->assertInstanceOf(User::class, $penjemputan->staff);
    }

    /** @test */
    public function is_menunggu_returns_true_for_menunggu_status()
    {
        $penjemputan = Penjemputan::factory()->create(['status' => 'menunggu']);
        
        $this->assertTrue($penjemputan->isMenunggu());
    }

    /** @test */
    public function is_menunggu_returns_false_for_other_status()
    {
        $penjemputan = Penjemputan::factory()->create(['status' => 'selesai']);
        
        $this->assertFalse($penjemputan->isMenunggu());
    }

    /** @test */
    public function it_uses_correct_table_name()
    {
        $penjemputan = new Penjemputan();
        
        $this->assertEquals('penjemputan', $penjemputan->getTable());
    }

    /** @test */
    public function it_uses_correct_primary_key()
    {
        $penjemputan = new Penjemputan();
        
        $this->assertEquals('penjemputan_id', $penjemputan->getKeyName());
    }

    /** @test */
    public function it_casts_tgl_order_to_datetime()
    {
        $penjemputan = Penjemputan::factory()->create([
            'tgl_order' => '2026-02-09 10:00:00'
        ]);
        
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $penjemputan->tgl_order);
    }
}
