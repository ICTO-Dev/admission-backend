<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicantEducation extends Model
{
    use HasFactory;

    protected $table = 'applicant_educations';

    protected $fillable = [
        'application_id',
        'elem_name',
        'elem_grad_year',
        'elem_address',
        'elem_awards',
        'jhs_name',
        'jhs_grad_year',
        'jhs_address',
        'jhs_awards',
        'shs_name',
        'shs_grad_year',
        'shs_address',
        'shs_track',
        'shs_awards',
        'shs_avg_g11',
        'shs_avg_g12',
        'coll_name',
        'coll_years',
        'coll_address',
        'coll_course',
        'coll_gwa',
        'coll_awards',
        'first_gen_student',
        'family_college_count',
        'future_outlook',
    ];

    protected $casts = [
        'first_gen_student' => 'boolean',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
}
