<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengantaran extends Model
{
    use HasFactory;

    protected $table = 'pengantaran';
    protected $primaryKey = 'pengantaran_id';
    public $timestamps = true;

    protected $fillable = [
        'cucian_id',
        'kurir_id',
        'alamat_antar',
        'status',
        'tgl_berangkat',
        'foto',
        'catatan'
    ];

    protected $casts = [
        'tgl_berangkat' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function cucian()
    {
        return $this->belongsTo(Cucian::class, 'cucian_id', 'cucian_id');
    }

    public function kurir()
    {
        return $this->belongsTo(User::class, 'kurir_id', 'users_id');
    }

    // Helpers
    public function isMenunggu()
    {
        return $this->status === 'menunggu';
    }

    public function isDiproses()
    {
        return $this->status === 'diproses';
    }

    public function isSelesai()
    {
        return $this->status === 'selesai';
    }

    public function getStatusBadge()
    {
        $badges = [
            'menunggu' => 'alert-warning',
            'diproses' => 'alert-info',
            'selesai' => 'alert-success'
        ];
        return $badges[$this->status] ?? 'alert-secondary';
    }

    public function getStatusLabel()
    {
        $labels = [
            'menunggu' => 'Menunggu',
            'diproses' => 'Dalam Pengiriman',
            'selesai' => 'Terkirim'
        ];
        return $labels[$this->status] ?? 'Unknown';
    }

    public function hasKurir()
    {
        return !is_null($this->kurir_id);
    }

    public function getKurirNama()
    {
        return $this->hasKurir() ? $this->kurir->nama : 'Belum Ditugaskan';
    }
}