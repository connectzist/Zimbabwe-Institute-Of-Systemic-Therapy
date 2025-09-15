<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Student extends Model
{
    use HasFactory;

    protected $table = 'students';
    protected $primaryKey = 'id';

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'candidate_number',
        'email',
        'course',
        'course_id',
        'date_of_birth',
        'enrollment_date',
        'cell_No',        
        'address',        
        'group',          
        'occupation', 
        'id_number',
        'profile_picture',
        'nationality', 
        'emergency_contact',
        
        
    ];

    /**
     * Relationship: A student has many certificate, Diploma , Advanced Diploma Results records.
     */
    public function certresultsRecords()
    {
        return $this->hasMany(CertificateResult::class, 'student_id', 'id');
    }

    public function diplomaresultsRecords()
    {
    return $this->hasMany(DiplomaResult::class, 'student_id', 'id');
    }

    public function diplomaFinalRecords()
    {
    return $this->hasMany(DiplomaFinalModule::class, 'student_id', 'id');
    }
    

    public function advanceddiplomaresultsRecords()
    {
        return $this->hasMany(AdvancedDiplomaResult::class, 'student_id', 'id');
    }

    /**
     * Counting
     */
    public function courseModulesCount()
    {
        return $this->certresultsRecords->flatMap(function ($record) {
            return $record->courseModule;
        })->count();
    }

    /**
     * Concatenate first and last name.
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

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
        return $this->hasMany(AdvancedSemesterExam::class, 'student_id');
    }

    public function advancedFinalEvaluations()
    {
    return $this->hasMany(AdvancedFinalEvaluation::class, 'student_id');
    }


}
