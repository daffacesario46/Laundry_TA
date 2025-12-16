<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'users_id';
    public $timestamps = true;

    protected $fillable = [
        'role',
        'email',
        'nama',
        'password',
        'no_telp',
        'no_wa',
        'alamat',
        'foto',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function pelanggan()
    {
        return $this->hasOne(Pelanggan::class, 'users_id', 'users_id');
    }

    // Helpers
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function isKurir()
    {
        return $this->role === 'kurir';
    }

    public function isPelanggan()
    {
        return $this->role === 'pelanggan';
    }
}