<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';
    protected $primaryKey = 'pelanggan_id';
    public $timestamps = true;

    protected $fillable = [
        'users_id',
        'kategori_pelanggan',
        'nama',
        'no_telp',
        'no_wa',
        'alamat',
        'foto',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'users_id');
    }

    public function cucian()
    {
        return $this->hasMany(Cucian::class, 'pelanggan_id', 'pelanggan_id');
    }

    // Helpers
    public function isMember()
    {
        return $this->kategori_pelanggan === 'member';
    }

    public function isAktif()
    {
        return $this->status === 'aktif';
    }

    public function getTotalOrder()
    {
        return $this->cucian()->count();
    }

    public function getTotalSpending()
    {
        return $this->cucian()->sum('total_harga');
    }
}