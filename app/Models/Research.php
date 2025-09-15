<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Research extends Model
{

    protected $table = 'researches';
    protected $fillable = [
        'research_title', 
        'researcher', 
        'year_of_research', 
        'student_type', 
        'file_path'
    ];
}
