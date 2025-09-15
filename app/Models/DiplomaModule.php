<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiplomaModule extends Model
{
    use HasFactory;

    protected $fillable = ['course_code', 'course_title', 'credits', 'module_id'];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_code', 'id');
    }

    public function diploresultsRecord()
    {
        return $this->belongsTo(DiplomaResult::class, 'course_module');
    }

    public function diplomaResults()
    {
    return $this->hasMany(DiplomaResult::class, 'course_module');
    }

}
