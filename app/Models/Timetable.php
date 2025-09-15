<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Timetable extends Model
{
    use HasFactory;

    protected $table = 'timetables';
    protected $fillable = [
        'title',
        'file_path',
        'student_type',
    ];

}
