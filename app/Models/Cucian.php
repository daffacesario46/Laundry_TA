<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cucian extends Model
{
    use HasFactory;

    protected $table = 'cucian';
    protected $primaryKey = 'cucian_id';
    public $timestamps = true;

    protected $fillable = [
        'pelanggan_id',
        'layanan_id',
        'jenis_cucian',
        'metode_cuci',
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

    protected $casts = [
        'tgl_order' => 'datetime',
        'estimasi' => 'datetime',
        'tgl_selesai' => 'datetime',
        'tgl_diambil' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id', 'pelanggan_id');
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id', 'layanan_id');
    }

    public function detail()
    {
        return $this->hasMany(CucianDetail::class, 'cucian_id', 'cucian_id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'cucian_id', 'cucian_id');
    }

    public function penjemputan()
    {
        return $this->hasOne(Penjemputan::class, 'cucian_id', 'cucian_id');
    }

    public function pengantaran()
    {
        return $this->hasOne(Pengantaran::class, 'cucian_id', 'cucian_id');
    }

    public function staffJemput()
    {
        return $this->belongsTo(User::class, 'staff_jemput_id', 'users_id');
    }

    public function kurirAntar()
    {
        return $this->belongsTo(User::class, 'kurir_antar_id', 'users_id');
    }

    // ✅ TAMBAHAN BARU: Helper method untuk cek jenis cucian
    public function isKiloan()
    {
        return $this->jenis_cucian === 'kiloan';
    }

    public function isSatuan()
    {
        return $this->jenis_cucian === 'satuan';
    }

    public function getJenisCucianLabel()
    {
        return $this->jenis_cucian ? ucfirst($this->jenis_cucian) : '-';
    }

    // Helper Methods
    public function getNoOrder()
    {
        return 'WW' . str_pad($this->cucian_id, 5, '0', STR_PAD_LEFT);
    }

    public function getStatusBadge()
    {
        return match($this->status_cucian) {
            'menunggu' => 'bg-warning',
            'diproses' => 'bg-info',
            'selesai' => 'bg-success',
            'diambil' => 'bg-secondary',
            default => 'bg-light'
        };
    }

    public function getStatusLabel()
    {
        return match($this->status_cucian) {
            'menunggu' => 'Menunggu',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'diambil' => 'Diambil',
            default => ucfirst($this->status_cucian)
        };
    }

    // Payment Check Methods
    public function hasPembayaran()
    {
        return $this->pembayaran !== null;
    }

    public function isPaid()
    {
        return $this->pembayaran && $this->pembayaran->status_bayar === 'lunas';
    }

    public function isUnpaid()
    {
        return !$this->pembayaran || $this->pembayaran->status_bayar === 'belum';
    }

    public function isPenjemputanSelesai()
    {
        return $this->penjemputan && $this->penjemputan->status === 'selesai';
    }

    public function canBeProcessed()
    {
        if ($this->jenis_order === 'offline') {
            return $this->isPaid();
        }
        
        if ($this->jenis_order === 'online') {
            return $this->isPenjemputanSelesai() && $this->isPaid();
        }
        
        return false;
    }

    public function canBeDelivered()
    {
        return $this->status_cucian === 'selesai' 
               && $this->jenis_order === 'online'
               && $this->jenis_ambil === 'diantar';
    }

    public function getCannotProcessReason()
    {
        if ($this->jenis_order === 'offline') {
            if ($this->isUnpaid()) {
                return 'Pembayaran belum lunas';
            }
        }
        
        if ($this->jenis_order === 'online') {
            if (!$this->penjemputan) {
                return 'Belum ada penjemputan';
            }
            if ($this->penjemputan->status !== 'selesai') {
                return 'Penjemputan belum selesai (Status: ' . ucfirst($this->penjemputan->status) . ')';
            }
            if ($this->isUnpaid()) {
                return 'Pembayaran belum lunas';
            }
        }
        
        return null;
    }

    public function isOnline()
    {
        return $this->jenis_order === 'online';
    }

    public function isOffline()
    {
        return $this->jenis_order === 'offline';
    }

    public function needsDelivery()
    {
        return $this->jenis_ambil === 'diantar';
    }

    public function getFormattedTotalHarga()
    {
        if ($this->total_harga) {
            return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
        }
        return '-';
    }

        /**
     * Reduce stok plastik saat cucian selesai
     */
    public function reduceStokPlastik()
    {
        if ($this->jenis_cucian === 'kiloan') {
            // Kurangi 1 plastik kiloan
            $plastikKiloan = \App\Models\StokBahan::where('jenis_bahan', 'Plastik Laundry')
                                                ->where('merk', 'Kiloan')
                                                ->first();
            if ($plastikKiloan && $plastikKiloan->stok_tersedia > 0) {
                $plastikKiloan->decrement('stok_tersedia', 1);
                
                \Log::info("Stok plastik kiloan berkurang", [
                    'cucian_id' => $this->cucian_id,
                    'sisa_stok' => $plastikKiloan->stok_tersedia
                ]);
            }
        } else {
            // Kurangi plastik satuan sesuai total_item
            $plastikSatuan = \App\Models\StokBahan::where('jenis_bahan', 'Plastik Laundry')
                                                ->where('merk', 'Satuan')
                                                ->first();
            if ($plastikSatuan && $plastikSatuan->stok_tersedia >= $this->total_item) {
                $plastikSatuan->decrement('stok_tersedia', $this->total_item);
                
                \Log::info("Stok plastik satuan berkurang", [
                    'cucian_id' => $this->cucian_id,
                    'jumlah' => $this->total_item,
                    'sisa_stok' => $plastikSatuan->stok_tersedia
                ]);
            }
        }
    }
}