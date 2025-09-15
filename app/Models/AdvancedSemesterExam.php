<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdvancedSemesterExam extends Model
{
   use HasFactory;

    protected $fillable = [
        'student_id',
        'module_id',
        'exam_mark',
        'is_published',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
