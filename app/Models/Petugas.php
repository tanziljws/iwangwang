<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Petugas extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'petugas';
    
    protected $fillable = [
        'nama_petugas',
        'username',
        'password',
        'email',
        'no_hp',
        'jabatan',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Accessor untuk nama
    public function getNamaAttribute()
    {
        return $this->nama_petugas;
    }

    // Accessor untuk role
    public function getRoleAttribute()
    {
        return $this->jabatan;
    }

    // Method untuk autentikasi
    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getAuthIdentifierName()
    {
        return 'username';
    }
    
    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifier()
    {
        return $this->username;
    }
}
