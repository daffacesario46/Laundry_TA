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
        'deskripsi'
    ];

    protected $casts = [
        'berat_kg' => 'double',
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
        if ($this->berat_kg && $this->listHarga->harga_kiloan) {
            return $this->berat_kg * $this->listHarga->harga_kiloan;
        }
        return $this->jumlah * $this->listHarga->harga_satuan;
    }

    public function getFormattedSubtotal()
    {
        return 'Rp ' . number_format($this->getSubtotal(), 0, ',', '.');
    }
}
