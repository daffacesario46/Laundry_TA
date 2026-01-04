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

    public function hasKurir()
    {
        return !is_null($this->kurir_id) && $this->kurir;
    }

    public function getKurirNama()
    {
        return $this->kurir ? $this->kurir->nama : '-';
    }

    public function getStatusLabel()
    {
        return match($this->status) {
            'menunggu' => 'Menunggu',
            'diproses' => 'Sedang Diantar',
            'selesai' => 'Selesai',
            default => $this->status
        };
    }

    public function getStatusBadge()
    {
        return match($this->status) {
            'menunggu' => 'bg-warning',
            'diproses' => 'bg-info',
            'selesai' => 'bg-success',
            default => 'bg-secondary'
        };
    }
}