<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // add auth
    protected $guard = 'web'; 

    public function isCertificateAdmin()
    {
        return $this->role === 'CertificateAdmin';
    }

    public function isDiplomaAdmin()
    {
        return $this->role === 'DiplomaAdmin';
    }

    public function isAdvancedDiplomaAdmin()
    {
        return $this->role === 'AdvancedDiplomaAdmin';
    }

}
