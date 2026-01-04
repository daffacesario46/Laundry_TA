<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    public function hasStaff()
    {
        return !is_null($this->staff_id) && $this->staff;
    }

    public function getStaffNama()
    {
        return $this->staff ? $this->staff->nama : '-';
    }

    public function getStatusLabel()
    {
        return match($this->status) {
            'menunggu' => 'Menunggu',
            'diproses' => 'Sedang Dijemput',
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