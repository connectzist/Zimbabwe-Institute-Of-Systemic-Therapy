<?php

namespace App\Models;

use App\Models\Student;
use Illuminate\Database\Eloquent\Model;

class CertificateResult extends Model
{
    protected $table = 'certresults_records'; 

    protected $fillable = ['student_id', 'course_module', 'module_id', 'theory_mark','practical_mark', 'exam_mark', 'average_mark',];  


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
         return $this->belongsTo(Module::class, 'module_id');
    }

        public function Module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

}
