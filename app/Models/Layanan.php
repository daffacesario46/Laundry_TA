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
        'durasi_hari',
        'harga'
    ];

    protected $casts = [
        'harga' => 'double',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function cucian()
    {
        return $this->hasMany(Cucian::class, 'layanan_id', 'layanan_id');
    }

    // Helpers


    const EXPRESS_MARKUP = 0.30; // 30%

    // ✅ METHOD BARU: Hitung Estimasi Berdasarkan Metode Cuci
    /**
     * Hitung estimasi selesai berdasarkan metode cuci
     * @param string $metodeCuci 'normal' atau 'express'
     * @param Carbon|null $tglOrder tanggal order (default: sekarang)
     * @return Carbon
     */
    public function hitungEstimasi($metodeCuci = 'normal', $tglOrder = null)
    {
        $tglOrder = $tglOrder ?: Carbon::now();
        
        if ($metodeCuci === 'express') {
            // ✅ EXPRESS: FIXED 24 JAM untuk semua layanan
            return $tglOrder->copy()->addHours(24);
        } else {
            // NORMAL: Gunakan durasi_hari dari layanan
            $hari = $this->durasi_hari ?? 3;
            return $tglOrder->copy()->addDays($hari);
        }
    }

    /**
     * Get formatted durasi text berdasarkan metode cuci
     */
    public function getFormattedDurasiText($metodeCuci = 'normal')
    {
        if ($metodeCuci === 'express') {
            return '24 Jam';
        } else {
            $hari = $this->durasi_hari ?? 3;
            return $hari . ' Hari';
        }
    }

    /**
     * Get harga normal (formatted)
     */
    public function getFormattedHarga()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    /**
     * Get harga express (harga + 30%)
     */
    public function getHargaExpress()
    {
        return $this->harga * (1 + self::EXPRESS_MARKUP);
    }

    /**
     * Get harga express (formatted)
     */
    public function getFormattedHargaExpress()
    {
        return 'Rp ' . number_format($this->getHargaExpress(), 0, ',', '.');
    }

    /**
     * Get harga by type (normal/express)
     */
    public function getHargaByType($type = 'normal')
    {
        if ($type === 'express') {
            return $this->getHargaExpress();
        }
        return $this->harga;
    }

    /**
     * Get markup percentage text
     */
    public function getExpressMarkupText()
    {
        return '+' . (self::EXPRESS_MARKUP * 100) . '%';
    }


    public function isKiloan()
    {
        return $this->jenis_cucian === 'kiloan';
    }

    public function isSatuan()
    {
        return $this->jenis_cucian === 'satuan';
    }
}