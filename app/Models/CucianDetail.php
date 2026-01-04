<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CucianDetail extends Model
{
    use HasFactory;

    protected $table = 'cucian_detail';
    protected $primaryKey = 'cucian_detail_id';
    public $timestamps = true;

    protected $fillable = [
        'cucian_id',
        'list_harga_id',
        'jumlah',
        'berat_kg',
        'harga_satuan',
        'harga_kiloan',
        'deskripsi'
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'berat_kg' => 'double',
        'harga_satuan' => 'double',
        'harga_kiloan' => 'double',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function cucian()
    {
        return $this->belongsTo(Cucian::class, 'cucian_id', 'cucian_id');
    }

    public function listHarga()
    {
        return $this->belongsTo(ListHarga::class, 'list_harga_id', 'list_harga_id');
    }

    // Helpers
    public function getSubtotal()
    {
        // Prioritas pakai harga historis dari detail
        if ($this->berat_kg && $this->berat_kg > 0) {
            // KILOAN
            $harga = $this->harga_kiloan ?? $this->listHarga->harga_kiloan ?? 0;
            return $this->berat_kg * $harga;
        }
        
        // SATUAN
        $harga = $this->harga_satuan ?? $this->listHarga->harga_satuan ?? 0;
        $jumlah = $this->jumlah ?? 1;
        return $jumlah * $harga;
    }

    public function getFormattedSubtotal()
    {
        return 'Rp ' . number_format($this->getSubtotal(), 0, ',', '.');
    }

    public function isSatuan()
    {
        return (!$this->berat_kg || $this->berat_kg == 0) && $this->jumlah > 0;
    }

    public function isKiloan()
    {
        return $this->berat_kg && $this->berat_kg > 0;
    }
}