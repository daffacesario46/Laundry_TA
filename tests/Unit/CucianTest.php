<?php

namespace Tests\Unit;

use App\Models\Cucian;
use App\Models\Pelanggan;
use App\Models\Layanan;
use App\Models\CucianDetail;
use App\Models\Pembayaran;
use App\Models\Penjemputan;
use App\Models\Pengantaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CucianTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'pelanggan_id',
            'layanan_id',
            'jenis_cucian',
            'jenis_order',
            'jenis_ambil',
            'tgl_order',
            'estimasi',
            'tgl_selesai',
            'tgl_diambil',
            'total_item',
            'total_berat',
            'total_harga',
            'status_cucian',
            'catatan',
            'staff_jemput_id',
            'kurir_antar_id'
        ];

        $cucian = new Cucian();
        $this->assertEquals($fillable, $cucian->getFillable());
    }

    /** @test */
    public function it_belongs_to_pelanggan()
    {
        $cucian = Cucian::factory()->create();
        
        $this->assertInstanceOf(Pelanggan::class, $cucian->pelanggan);
    }

    /** @test */
    public function it_belongs_to_layanan()
    {
        $cucian = Cucian::factory()->create();
        
        $this->assertInstanceOf(Layanan::class, $cucian->layanan);
    }

    /** @test */
    public function it_has_many_detail()
    {
        $cucian = Cucian::factory()->create();
        CucianDetail::factory()->create(['cucian_id' => $cucian->cucian_id]);

        $this->assertInstanceOf(CucianDetail::class, $cucian->detail->first());
    }

    /** @test */
    public function it_has_one_pembayaran()
    {
        $cucian = Cucian::factory()->create();
        Pembayaran::factory()->create(['cucian_id' => $cucian->cucian_id]);

        $this->assertInstanceOf(Pembayaran::class, $cucian->pembayaran);
    }

    /** @test */
    public function it_has_one_penjemputan()
    {
        $cucian = Cucian::factory()->create();
        Penjemputan::factory()->create(['cucian_id' => $cucian->cucian_id]);

        $this->assertInstanceOf(Penjemputan::class, $cucian->penjemputan);
    }

    /** @test */
    public function it_has_one_pengantaran()
    {
        $cucian = Cucian::factory()->create();
        Pengantaran::factory()->create(['cucian_id' => $cucian->cucian_id]);

        $this->assertInstanceOf(Pengantaran::class, $cucian->pengantaran);
    }

    /** @test */
    public function is_kiloan_returns_true_for_kiloan()
    {
        $cucian = Cucian::factory()->create(['jenis_cucian' => 'kiloan']);
        
        $this->assertTrue($cucian->isKiloan());
    }

    /** @test */
    public function is_satuan_returns_true_for_satuan()
    {
        $cucian = Cucian::factory()->create(['jenis_cucian' => 'satuan']);
        
        $this->assertTrue($cucian->isSatuan());
    }

    /** @test */
    public function get_jenis_cucian_label_returns_capitalized_value()
    {
        $cucian = Cucian::factory()->create(['jenis_cucian' => 'kiloan']);
        
        $this->assertEquals('Kiloan', $cucian->getJenisCucianLabel());
    }

    /** @test */
    public function get_no_order_returns_formatted_order_number()
    {
        $cucian = Cucian::factory()->create(['cucian_id' => 123]);
        
        $this->assertEquals('WW00123', $cucian->getNoOrder());
    }

    /** @test */
    public function get_status_badge_returns_correct_class()
    {
        $cucian = Cucian::factory()->create(['status_cucian' => 'menunggu']);
        $this->assertEquals('bg-warning', $cucian->getStatusBadge());

        $cucian->status_cucian = 'diproses';
        $this->assertEquals('bg-info', $cucian->getStatusBadge());

        $cucian->status_cucian = 'selesai';
        $this->assertEquals('bg-success', $cucian->getStatusBadge());

        $cucian->status_cucian = 'diambil';
        $this->assertEquals('bg-secondary', $cucian->getStatusBadge());
    }

    /** @test */
    public function get_status_label_returns_correct_label()
    {
        $cucian = Cucian::factory()->create(['status_cucian' => 'menunggu']);
        
        $this->assertEquals('Menunggu', $cucian->getStatusLabel());
    }

    /** @test */
    public function has_pembayaran_returns_true_when_pembayaran_exists()
    {
        $cucian = Cucian::factory()->create();
        Pembayaran::factory()->create(['cucian_id' => $cucian->cucian_id]);

        $this->assertTrue($cucian->hasPembayaran());
    }

    /** @test */
    public function is_paid_returns_true_when_lunas()
    {
        $cucian = Cucian::factory()->create();
        Pembayaran::factory()->create([
            'cucian_id' => $cucian->cucian_id,
            'status_bayar' => 'lunas'
        ]);

        $this->assertTrue($cucian->isPaid());
    }

    /** @test */
    public function is_unpaid_returns_true_when_no_pembayaran()
    {
        $cucian = Cucian::factory()->create();

        $this->assertTrue($cucian->isUnpaid());
    }

    /** @test */
    public function is_online_returns_true_for_online_order()
    {
        $cucian = Cucian::factory()->create(['jenis_order' => 'online']);
        
        $this->assertTrue($cucian->isOnline());
    }

    /** @test */
    public function is_offline_returns_true_for_offline_order()
    {
        $cucian = Cucian::factory()->create(['jenis_order' => 'offline']);
        
        $this->assertTrue($cucian->isOffline());
    }

    /** @test */
    public function needs_delivery_returns_true_when_diantar()
    {
        $cucian = Cucian::factory()->create(['jenis_ambil' => 'diantar']);
        
        $this->assertTrue($cucian->needsDelivery());
    }

    /** @test */
    public function can_be_processed_returns_true_for_paid_offline_order()
    {
        $cucian = Cucian::factory()->create(['jenis_order' => 'offline']);
        Pembayaran::factory()->create([
            'cucian_id' => $cucian->cucian_id,
            'status_bayar' => 'lunas'
        ]);

        $this->assertTrue($cucian->canBeProcessed());
    }

    /** @test */
    public function can_be_processed_returns_false_for_unpaid_offline_order()
    {
        $cucian = Cucian::factory()->create(['jenis_order' => 'offline']);

        $this->assertFalse($cucian->canBeProcessed());
    }

    /** @test */
    public function can_be_processed_returns_true_for_online_order_with_completed_pickup_and_payment()
    {
        $cucian = Cucian::factory()->create(['jenis_order' => 'online']);
        Penjemputan::factory()->create([
            'cucian_id' => $cucian->cucian_id,
            'status' => 'selesai'
        ]);
        Pembayaran::factory()->create([
            'cucian_id' => $cucian->cucian_id,
            'status_bayar' => 'lunas'
        ]);

        $this->assertTrue($cucian->canBeProcessed());
    }

    /** @test */
    public function can_be_delivered_returns_true_when_conditions_met()
    {
        $cucian = Cucian::factory()->create([
            'status_cucian' => 'selesai',
            'jenis_order' => 'online',
            'jenis_ambil' => 'diantar'
        ]);

        $this->assertTrue($cucian->canBeDelivered());
    }

    /** @test */
    public function get_formatted_total_harga_returns_formatted_currency()
    {
        $cucian = Cucian::factory()->create(['total_harga' => 150000]);
        
        $this->assertEquals('Rp 150.000', $cucian->getFormattedTotalHarga());
    }

    /** @test */
    public function get_cannot_process_reason_returns_reason_for_unpaid_offline()
    {
        $cucian = Cucian::factory()->create(['jenis_order' => 'offline']);
        
        $this->assertEquals('Pembayaran belum lunas', $cucian->getCannotProcessReason());
    }

    /** @test */
    public function get_cannot_process_reason_returns_reason_for_online_without_pickup()
    {
        $cucian = Cucian::factory()->create(['jenis_order' => 'online']);
        
        $this->assertEquals('Belum ada penjemputan', $cucian->getCannotProcessReason());
    }

    /** @test */
    public function is_penjemputan_selesai_returns_true_when_completed()
    {
        $cucian = Cucian::factory()->create();
        Penjemputan::factory()->create([
            'cucian_id' => $cucian->cucian_id,
            'status' => 'selesai'
        ]);

        $this->assertTrue($cucian->isPenjemputanSelesai());
    }
}
