<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PastExamPaper extends Model
{
    use HasFactory;

    protected $table = 'past_exam_papers';
    protected $fillable = [
        'course_id',
        'exam_title',
        'exam_year',
        'module_name',
        'file_path',
    ];

    // Relationship
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }
}
