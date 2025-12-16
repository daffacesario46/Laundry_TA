<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanan';
    protected $primaryKey = 'layanan_id';
    public $timestamps = true;

    protected $fillable = [
        'nama_layanan',
        'jenis_cucian',
        'deskripsi',
        'durasi_hari'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function cucian()
    {
        return $this->hasMany(Cucian::class, 'layanan_id', 'layanan_id');
    }

    // Helpers
    public function isKiloan()
    {
        return $this->jenis_cucian === 'kiloan';
    }

    public function isSatuan()
    {
        return $this->jenis_cucian === 'satuan';
    }
}