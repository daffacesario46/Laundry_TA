<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjemputan extends Model
{
    use HasFactory;

    protected $table = 'penjemputan';
    protected $primaryKey = 'penjemputan_id';
    public $timestamps = true;

    protected $fillable = [
        'cucian_id',
        'staff_id',
        'alamat_jemput',
        'status',
        'tgl_order',
        'foto',
        'catatan'
    ];

    protected $casts = [
        'tgl_order' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function cucian()
    {
        return $this->belongsTo(Cucian::class, 'cucian_id', 'cucian_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id', 'users_id');
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
            'diproses' => 'Sedang Dijemput',
            'selesai' => 'Selesai'
        ];
        return $labels[$this->status] ?? 'Unknown';
    }

    public function hasStaff()
    {
        return !is_null($this->staff_id);
    }

    public function getStaffNama()
    {
        return $this->hasStaff() ? $this->staff->nama : 'Belum Ditugaskan';
    }
}