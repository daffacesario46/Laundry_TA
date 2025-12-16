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
        return !is_null($this->harga_kiloan);
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
