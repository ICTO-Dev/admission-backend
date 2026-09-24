<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicantFamily extends Model
{
    use HasFactory;

    protected $table = 'applicant_family_profiles';

    protected $fillable = [
        'application_id',
        'father_name',
        'father_age',
        'father_birthplace',
        'father_education',
        'father_contact',
        'father_occupation',
        'father_workplace',
        'father_living_status',
        'father_cause_of_death',
        'father_living_with_family',
        'mother_name',
        'mother_age',
        'mother_birthplace',
        'mother_education',
        'mother_contact',
        'mother_occupation',
        'mother_workplace',
        'mother_living_status',
        'mother_cause_of_death',
        'mother_living_with_family',
        'spouse_name',
        'spouse_age',
        'spouse_birthplace',
        'spouse_education',
        'spouse_contact',
        'spouse_occupation',
        'spouse_workplace',
        'spouse_living_status',
        'spouse_living_with_family',
        'spouse_dependents',
        'birth_order',
        'birth_order_other',
        'housing_condition',
        'family_monthly_income',
        'language_spoken',
    ];

    protected $casts = [
        'father_age' => 'integer',
        'mother_age' => 'integer',
        'spouse_age' => 'integer',
        'spouse_dependents' => 'integer',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
}
