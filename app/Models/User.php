<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     */
    protected $table = 'users';
    protected $primaryKey = 'users_id';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     */
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

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'password' => 'hashed',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Relasi ke Pelanggan (One to One)
     */
    public function pelanggan()
    {
        return $this->hasOne(Pelanggan::class, 'users_id', 'users_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Role Checker Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is staff
     */
    public function isStaff()
    {
        return $this->role === 'staff';
    }

    /**
     * Check if user is kurir
     */
    public function isKurir()
    {
        return $this->role === 'kurir';
    }

    /**
     * Check if user is pelanggan
     */
    public function isPelanggan()
    {
        return $this->role === 'pelanggan';
    }

    /**
     * Check if user is active
     */
    public function isActive()
    {
        return $this->status === 'aktif';
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Get role label in Indonesian
     */
    public function getRoleLabel()
    {
        $labels = [
            'admin' => 'Administrator',
            'staff' => 'Staff',
            'kurir' => 'Kurir',
            'pelanggan' => 'Pelanggan',
        ];
        
        return $labels[$this->role] ?? ucfirst($this->role);
    }

    /**
     * Get status badge color
     */
    public function getStatusBadge()
    {
        return $this->status === 'aktif' ? 'bg-success' : 'bg-danger';
    }

    /**
     * Get status label
     */
    public function getStatusLabel()
    {
        return ucfirst($this->status);
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope untuk filter by role
     */
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope untuk user aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope untuk user non-aktif
     */
    public function scopeNonaktif($query)
    {
        return $query->where('status', 'nonaktif');
    }

    /**
     * Scope untuk admin
     */
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope untuk staff
     */
    public function scopeStaff($query)
    {
        return $query->where('role', 'staff');
    }

    /**
     * Scope untuk kurir
     */
    public function scopeKurir($query)
    {
        return $query->where('role', 'kurir');
    }

    /**
     * Scope untuk pelanggan
     */
    public function scopePelanggan($query)
    {
        return $query->where('role', 'pelanggan');
    }
}