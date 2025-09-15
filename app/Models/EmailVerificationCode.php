<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class EmailVerificationCode extends Model
{
    protected $fillable = [
        'email',
        'code',
        'expires_at',
    ];

    public $timestamps = true;

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
