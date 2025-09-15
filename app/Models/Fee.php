<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'amount',
        'description',
        'due_date',
        'status',
        'payment_date',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'payment_date' => 'datetime',
    ];

    // Fees Relationship with student 
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
