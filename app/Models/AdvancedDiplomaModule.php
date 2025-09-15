<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdvancedDiplomaModule extends Model
{
    use HasFactory;
    protected $table = 'advanced_diploma_modules';
    protected $fillable = ['course_code', 'course_title', 'credits', 'module_id']; 

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_code', 'id');
    }

    public function advanced_diploresultsRecord()
    {
        return $this->belongsTo(AdvancedDiplomaResult::class, 'course_module');
    }

    public function advanceddiplomaResults()
    {
    return $this->hasMany(AdvancedDiplomaResult::class, 'course_module');
    }
}
