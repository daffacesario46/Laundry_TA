<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListHarga extends Model
{
    use HasFactory;

    protected $table = 'list_harga';
    protected $primaryKey = 'list_harga_id';
    public $timestamps = true;

    protected $fillable = [
        'nama_item',
        'harga_satuan',
        'harga_kiloan'
    ];

    protected $casts = [
        'harga_satuan' => 'double',
        'harga_kiloan' => 'double',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function cucianDetail()
    {
        return $this->hasMany(CucianDetail::class, 'list_harga_id', 'list_harga_id');
    }

    // Helpers
    public function hasHargaKiloan()
    {
        return !is_null($this->harga_kiloan) && $this->harga_kiloan > 0;
    }

    public function hasHargaSatuan()
    {
        return !is_null($this->harga_satuan) && $this->harga_satuan > 0;
    }

    // TAMBAHAN BARU: Accessor untuk tipe_harga
    public function getTipeHargaAttribute()
    {
        // Jika kedua harga ada, prioritas satuan
        if ($this->hasHargaSatuan() && $this->hasHargaKiloan()) {
            return 'satuan'; // bisa juga 'keduanya'
        }
        
        if ($this->hasHargaSatuan()) {
            return 'satuan';
        }
        
        if ($this->hasHargaKiloan()) {
            return 'kiloan';
        }
        
        return 'satuan'; // default
    }

    public function getFormattedHargaSatuan()
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }

    public function getFormattedHargaKiloan()
    {
        if ($this->hasHargaKiloan()) {
            return 'Rp ' . number_format($this->harga_kiloan, 0, ',', '.');
        }
        return '-';
    }
}