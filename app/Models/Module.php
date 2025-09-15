<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = 'modules'; 

    protected $fillable = [
        'module_name',
        'course_id',
        'reg_start',
        'reg_due',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class); 
    }

    public function registrations()
    {
    return $this->hasMany(Registration::class);
    }

    public function semesterExamRecords()
    {
    return $this->hasMany(AdvancedSemesterExam::class, 'module_id');
    }


}
