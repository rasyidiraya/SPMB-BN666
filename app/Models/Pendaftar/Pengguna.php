<?php

namespace App\Models\Pendaftar;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    protected $table = 'pengguna';
    
    protected $fillable = [
        'nama', 'email', 'hp', 'password_hash', 'role', 'aktif', 'google_id'
    ];

    protected $hidden = ['password_hash', 'remember_token'];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}