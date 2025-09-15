<?php

namespace App\Models;
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CertificateModule extends Model
{
    use HasFactory;

    protected $table = 'certificate_modules';

    protected $fillable = [
        'course_code',
        'course_title',
        'credits',
        'module_id', 
    ];

    /**
     * Relationship: A course module belongs to a certificate result.
     */
    public function certresultsRecord()
    {
        return $this->belongsTo(CertificateResult::class, 'course_module');
    }

    public function certificateResults()
    {
    return $this->hasMany(CertificateResult::class, 'course_module');
    }

}
