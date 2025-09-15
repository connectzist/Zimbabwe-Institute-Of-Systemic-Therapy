<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ImapUser extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['email', 'name'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return null;
    }
}
