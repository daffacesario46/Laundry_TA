<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    protected $primaryKey = 'pembayaran_id';
    public $timestamps = true;

    protected $fillable = [
        'cucian_id',
        'metode_bayar',
        'status_bayar',
        'jumlah_bayar',
        'tgl_bayar',
        'bukti_bayar',
        'catatan'
    ];

    protected $casts = [
        'jumlah_bayar' => 'double',
        'tgl_bayar' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function cucian()
    {
        return $this->belongsTo(Cucian::class, 'cucian_id', 'cucian_id');
    }

    // Helpers
    public function isLunas()
    {
        return $this->status_bayar === 'lunas';
    }

    public function isBelum()
    {
        return $this->status_bayar === 'belum';
    }

    public function getStatusBadge()
    {
        return $this->isLunas() ? 'alert-success' : 'alert-danger';
    }

    public function getStatusLabel()
    {
        return $this->isLunas() ? 'Lunas' : 'Belum Bayar';
    }

    public function getFormattedJumlahBayar()
    {
        return 'Rp ' . number_format($this->jumlah_bayar, 0, ',', '.');
    }

    public function getMetodeBayarLabel()
    {
        return ucfirst($this->metode_bayar);
    }
}