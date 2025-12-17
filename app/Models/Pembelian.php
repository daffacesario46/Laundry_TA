<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';
    protected $primaryKey = 'pembelian_id';
    public $timestamps = true;

    protected $fillable = [
        'kode_beli',
        'wkt_beli',
        'tanggal_beli',
        'jam_beli',
        'jenis_bahan',
        'merk',
        'jumlah_beli',
        'total_harga',
        'bukti'
    ];

    protected $casts = [
        'wkt_beli' => 'datetime',
        'tanggal_beli' => 'date',
        'jumlah_beli' => 'double',
        'total_harga' => 'double',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function pemakaian()
    {
        return $this->hasMany(Pemakaian::class, 'pembelian_id', 'pembelian_id');
    }

    // Helpers
    public function getFormattedTotalHarga()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    public function getFormattedJumlahBeli()
    {
        return number_format($this->jumlah_beli, 2);
    }

    public function getHargaPerUnit()
    {
        if ($this->jumlah_beli > 0) {
            return $this->total_harga / $this->jumlah_beli;
        }
        return 0;
    }

    public function getFormattedHargaPerUnit()
    {
        return 'Rp ' . number_format($this->getHargaPerUnit(), 0, ',', '.');
    }

    public function getTotalTerpakai()
    {
        return $this->pemakaian()->sum('jumlah_terpakai');
    }

    public function getSisaStok()
    {
        return $this->jumlah_beli - $this->getTotalTerpakai();
    }

    public function getFormattedSisaStok()
    {
        return number_format($this->getSisaStok(), 2);
    }

    public function hasBukti()
    {
        return !is_null($this->bukti);
    }
}