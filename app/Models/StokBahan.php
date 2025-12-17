<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokBahan extends Model
{
    use HasFactory;

    protected $table = 'stok_bahan';
    protected $primaryKey = 'stok_bahan_id';
    public $timestamps = true;

    protected $fillable = [
        'jenis_bahan',
        'merk',
        'stok_tersedia',
        'satuan',
        'harga_beli',
        'stok_minimum',
        'deskripsi'
    ];

    protected $casts = [
        'stok_tersedia' => 'double',
        'harga_beli' => 'double',
        'stok_minimum' => 'double',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function pemakaian()
    {
        return $this->hasMany(Pemakaian::class, 'stok_bahan_id', 'stok_bahan_id');
    }

    // Helpers
    public function isStokRendah()
    {
        if (is_null($this->stok_minimum)) {
            return false;
        }
        return $this->stok_tersedia <= $this->stok_minimum;
    }

    public function isStokHabis()
    {
        return $this->stok_tersedia <= 0;
    }

    public function getStokBadge()
    {
        if ($this->isStokHabis()) {
            return 'alert-danger';
        }
        if ($this->isStokRendah()) {
            return 'alert-warning';
        }
        return 'alert-success';
    }

    public function getStokLabel()
    {
        if ($this->isStokHabis()) {
            return 'Stok Habis';
        }
        if ($this->isStokRendah()) {
            return 'Stok Rendah';
        }
        return 'Stok Aman';
    }

    public function getFormattedStok()
    {
        return number_format($this->stok_tersedia, 2) . ' ' . $this->satuan;
    }

    public function getFormattedHargaBeli()
    {
        return 'Rp ' . number_format($this->harga_beli, 0, ',', '.');
    }

    public function getNilaiStok()
    {
        return $this->stok_tersedia * $this->harga_beli;
    }

    public function getFormattedNilaiStok()
    {
        return 'Rp ' . number_format($this->getNilaiStok(), 0, ',', '.');
    }
}