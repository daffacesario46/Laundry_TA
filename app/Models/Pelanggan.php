<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    /**
     * ✅ NEW: Get foto URL with fallback to default avatar
     */
    public function getFotoUrl()
    {
        if ($this->foto && Storage::disk('public')->exists($this->foto)) {
            return Storage::url($this->foto);
        }
        
        // Default avatar berdasarkan kategori
        if ($this->isMember()) {
            return asset('assets/images/default-member.png');
        }
        
        return asset('assets/images/default-avatar.png');
    }

    /**
     * ✅ NEW: Check if has custom foto
     */
    public function hasFoto()
    {
        return $this->foto && Storage::disk('public')->exists($this->foto);
    }

    /**
     * ✅ NEW: Get foto thumbnail URL (for list view)
     */
    public function getFotoThumbnail()
    {
        // Jika ada foto, return URL-nya
        if ($this->hasFoto()) {
            return Storage::url($this->foto);
        }
        
        // Jika tidak ada foto, return initial avatar (huruf pertama nama)
        $initial = strtoupper(substr($this->nama, 0, 1));
        
        // Generate warna background berdasarkan nama
        $colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#FFA07A', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E2'];
        $colorIndex = ord($initial) % count($colors);
        $bgColor = $colors[$colorIndex];
        
        // Return data URL untuk avatar dengan initial
        return "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100'%3E%3Crect width='100' height='100' fill='{$bgColor}'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' fill='white' font-size='40' font-weight='bold'%3E{$initial}%3C/text%3E%3C/svg%3E";
    }

    /**
     * ✅ NEW: Format kategori pelanggan
     */
    public function getKategoriLabel()
    {
        return $this->isMember() ? 'Member' : 'Umum';
    }

    /**
     * ✅ NEW: Get badge class untuk kategori
     */
    public function getKategoriBadge()
    {
        return $this->isMember() ? 'badge bg-success' : 'badge bg-secondary';
    }

    /**
     * ✅ NEW: Get status badge class
     */
    public function getStatusBadge()
    {
        return $this->isAktif() ? 'badge bg-success' : 'badge bg-warning';
    }
}