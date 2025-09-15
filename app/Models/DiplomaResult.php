<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiplomaResult extends Model
{
    protected $table = 'diplomaresults_records';
    protected $fillable = [
        'student_id',
        'module_id',
        'exam_mark',
        'practical_mark',
        'theory_mark',
    ];

    /**
     * Relationship: A record belongs to a student and course module.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function courseModule()
    {
         return $this->belongsTo(Module::class, 'module_id');
    }

}
