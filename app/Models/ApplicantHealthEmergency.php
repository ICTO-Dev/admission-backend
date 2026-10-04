<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicantHealthEmergency extends Model
{
    use HasFactory;

    protected $table = 'applicant_health_emergency';

    protected $fillable = [
        'application_id',
        'pwd_status',
        'pwd_specs',
        'hospitalized_status',
        'hospitalized_reasons',
        'emergency_name',
        'emergency_relation',
        'emergency_contact',
        'emergency_address',
    ];

    protected $casts = [
        'pwd_status' => 'boolean',
        'hospitalized_status' => 'boolean',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
}
