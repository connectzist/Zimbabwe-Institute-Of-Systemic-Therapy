<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdvancedFinalEvaluation extends Model
{
    use HasFactory;

    protected $table = 'advanced_final_evaluations'; 
    protected $fillable = [
        'student_id',
        'adft110_internal',
        'final_theory',
        'clinicals',
        'is_published',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}



























