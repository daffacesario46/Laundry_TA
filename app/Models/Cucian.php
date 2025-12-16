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
        'catatan'
    ];

    protected $casts = [
        'tgl_order' => 'datetime',
        'estimasi' => 'datetime',
        'tgl_selesai' => 'datetime',
        'tgl_diambil' => 'datetime',
        'total_berat' => 'double',
        'total_harga' => 'double',
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

    // Helpers
    public function getNoOrder()
    {
        return 'WW' . str_pad($this->cucian_id, 5, '0', STR_PAD_LEFT);
    }

    public function isOnline()
    {
        return $this->jenis_order === 'online';
    }

    public function isDiantar()
    {
        return $this->jenis_ambil === 'diantar';
    }

    public function getStatusBadge()
    {
        $badges = [
            'menunggu' => 'alert-warning',
            'diproses' => 'alert-info',
            'selesai' => 'alert-success',
            'diambil' => 'alert-secondary'
        ];
        return $badges[$this->status_cucian] ?? 'alert-secondary';
    }

    public function getStatusLabel()
    {
        $labels = [
            'menunggu' => 'Menunggu',
            'diproses' => 'Proses',
            'selesai' => 'Selesai',
            'diambil' => 'Diambil'
        ];
        return $labels[$this->status_cucian] ?? 'Unknown';
    }

    public function getFormattedTotalHarga()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    public function hasPembayaran()
    {
        return $this->pembayaran()->exists();
    }

    public function isPaid()
    {
        return $this->hasPembayaran() && $this->pembayaran->status_bayar === 'lunas';
    }

    public function isUnpaid()
    {
        return !$this->hasPembayaran() || $this->pembayaran->status_bayar === 'belum';
    }
}
