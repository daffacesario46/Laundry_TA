<?php

namespace Tests\Unit;

use App\Models\Pembayaran;
use App\Models\Cucian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PembayaranTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'cucian_id',
            'metode_bayar',
            'status_bayar',
            'jumlah_bayar',
            'tgl_bayar',
            'bukti_bayar',
            'catatan',
            'snap_token',
            'transaction_id',
            'payment_type',
            'transaction_status',
        ];

        $pembayaran = new Pembayaran();
        $this->assertEquals($fillable, $pembayaran->getFillable());
    }

    /** @test */
    public function it_belongs_to_cucian()
    {
        $pembayaran = Pembayaran::factory()->create();
        
        $this->assertInstanceOf(Cucian::class, $pembayaran->cucian);
    }

    /** @test */
    public function is_lunas_returns_true_when_lunas()
    {
        $pembayaran = Pembayaran::factory()->create(['status_bayar' => 'lunas']);
        
        $this->assertTrue($pembayaran->isLunas());
    }

    /** @test */
    public function is_lunas_returns_false_when_belum()
    {
        $pembayaran = Pembayaran::factory()->create(['status_bayar' => 'belum']);
        
        $this->assertFalse($pembayaran->isLunas());
    }

    /** @test */
    public function is_belum_returns_true_when_belum()
    {
        $pembayaran = Pembayaran::factory()->create(['status_bayar' => 'belum']);
        
        $this->assertTrue($pembayaran->isBelum());
    }

    /** @test */
    public function is_belum_returns_false_when_lunas()
    {
        $pembayaran = Pembayaran::factory()->create(['status_bayar' => 'lunas']);
        
        $this->assertFalse($pembayaran->isBelum());
    }

    /** @test */
    public function get_status_badge_returns_success_when_lunas()
    {
        $pembayaran = Pembayaran::factory()->create(['status_bayar' => 'lunas']);
        
        $this->assertEquals('alert-success', $pembayaran->getStatusBadge());
    }

    /** @test */
    public function get_status_badge_returns_danger_when_belum()
    {
        $pembayaran = Pembayaran::factory()->create(['status_bayar' => 'belum']);
        
        $this->assertEquals('alert-danger', $pembayaran->getStatusBadge());
    }

    /** @test */
    public function get_status_label_returns_lunas_when_lunas()
    {
        $pembayaran = Pembayaran::factory()->create(['status_bayar' => 'lunas']);
        
        $this->assertEquals('Lunas', $pembayaran->getStatusLabel());
    }

    /** @test */
    public function get_status_label_returns_belum_bayar_when_belum()
    {
        $pembayaran = Pembayaran::factory()->create(['status_bayar' => 'belum']);
        
        $this->assertEquals('Belum Bayar', $pembayaran->getStatusLabel());
    }

    /** @test */
    public function get_formatted_jumlah_bayar_returns_formatted_currency()
    {
        $pembayaran = Pembayaran::factory()->create(['jumlah_bayar' => 250000]);
        
        $this->assertEquals('Rp 250.000', $pembayaran->getFormattedJumlahBayar());
    }

    /** @test */
    public function get_metode_bayar_label_returns_capitalized_value()
    {
        $pembayaran = Pembayaran::factory()->create(['metode_bayar' => 'cash']);
        
        $this->assertEquals('Cash', $pembayaran->getMetodeBayarLabel());
    }

    /** @test */
    public function it_uses_correct_table_name()
    {
        $pembayaran = new Pembayaran();
        
        $this->assertEquals('pembayaran', $pembayaran->getTable());
    }

    /** @test */
    public function it_uses_correct_primary_key()
    {
        $pembayaran = new Pembayaran();
        
        $this->assertEquals('pembayaran_id', $pembayaran->getKeyName());
    }

    /** @test */
    public function it_casts_jumlah_bayar_to_double()
    {
        $pembayaran = Pembayaran::factory()->create(['jumlah_bayar' => '100000']);
        
        $this->assertIsFloat($pembayaran->jumlah_bayar);
    }

    /** @test */
    public function it_casts_tgl_bayar_to_datetime()
    {
        $pembayaran = Pembayaran::factory()->create([
            'tgl_bayar' => '2026-02-09 10:00:00'
        ]);
        
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $pembayaran->tgl_bayar);
    }
}
