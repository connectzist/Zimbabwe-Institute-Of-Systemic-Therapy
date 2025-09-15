<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvancedDiplomaResult extends Model
{
    protected $table = 'advanceddiplomaresults_records'; 

    protected $fillable = ['student_id', 'course_module','module_id', 'mark'];  

    /**
     * Relationship: A certificate result belongs to a student.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Relationship: A certificate result belongs to a course module.
     */

    public function courseModule()
    {
    return $this->belongsTo(AdvancedDiplomaModule::class, 'course_module', 'id');
    }

    public function Module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

}
