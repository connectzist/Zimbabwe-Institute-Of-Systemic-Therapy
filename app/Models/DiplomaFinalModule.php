<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiplomaFinalModule extends Model
{
    use HasFactory;

    protected $table = 'diploma_final_module';

    protected $fillable = ['student_id', 'module_id', 'exam_mark', 'practical_mark','research_mark','is_published'];
    

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function courseModule()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
