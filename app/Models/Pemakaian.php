<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemakaian extends Model
{
    use HasFactory;

    protected $table = 'pemakaian';
    protected $primaryKey = 'pemakaian_id';
    public $timestamps = true;

    protected $fillable = [
        'pembelian_id',
        'stok_bahan_id',
        'jumlah_terpakai',
        'bukti'
    ];

    protected $casts = [
        'jumlah_terpakai' => 'double',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id', 'pembelian_id');
    }

    public function stokBahan()
    {
        return $this->belongsTo(StokBahan::class, 'stok_bahan_id', 'stok_bahan_id');
    }

    // Helpers
    public function getFormattedJumlahTerpakai()
    {
        $satuan = $this->stokBahan ? $this->stokBahan->satuan : '';
        return number_format($this->jumlah_terpakai, 2) . ' ' . $satuan;
    }

    public function getNilaiBahan()
    {
        if ($this->stokBahan && $this->stokBahan->harga_beli) {
            return $this->jumlah_terpakai * $this->stokBahan->harga_beli;
        }
        return 0;
    }

    public function getFormattedNilaiBahan()
    {
        return 'Rp ' . number_format($this->getNilaiBahan(), 0, ',', '.');
    }

    public function hasBukti()
    {
        return !is_null($this->bukti);
    }

    public function getJenisBahan()
    {
        return $this->stokBahan ? $this->stokBahan->jenis_bahan : '-';
    }

    public function getMerk()
    {
        return $this->stokBahan ? $this->stokBahan->merk : '-';
    }
}