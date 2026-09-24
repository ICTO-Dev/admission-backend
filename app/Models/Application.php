<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Application extends Model
{
    use HasFactory;

    protected $table = 'applications';

    protected $fillable = [
        'application_no',
        'school_year',
        'student_type',
        'campus_id',
        'course_1_id',
        'course_2_id',
        'barangay_id',
        'lrn',
        'last_name',
        'first_name',
        'middle_name',
        'date_of_birth',
        'age',
        'sex',
        'civil_status',
        'place_of_birth',
        'religion',
        'nationality',
        'present_address',
        'permanent_address',
        'mobile_number',
        'email_address',
        'photo_url',
        'is_indigenous',
        'indigenous_group',
        'is_solo_parent',
        'status',
        'exam_schedule_slot_id',
    ];

    protected $casts = [
        'is_indigenous' => 'boolean',
        'is_solo_parent' => 'boolean',
        'date_of_birth' => 'date',
        'age' => 'integer',
        'campus_id' => 'integer',
        'course_1_id' => 'integer',
        'course_2_id' => 'integer',
        'barangay_id' => 'string',
    ];

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class, 'campus_id');
    }

    public function firstCourse(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_1_id');
    }

    public function secondCourse(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_2_id');
    }

    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class, 'barangay_id');
    }

    public function family(): HasOne
    {
        return $this->hasOne(ApplicantFamily::class, 'application_id');
    }

    public function siblings(): HasMany
    {
        return $this->hasMany(ApplicantSibling::class, 'application_id');
    }

    public function education(): HasOne
    {
        return $this->hasOne(ApplicantEducation::class, 'application_id');
    }

    public function healthEmergency(): HasOne
    {
        return $this->hasOne(ApplicantHealthEmergency::class, 'application_id');
    }

    // Accessors for convenience and backward compatibility
    public function getCampusNameAttribute(): ?string
    {
        return $this->campus?->name;
    }

    public function getCourseApplied1stAttribute(): ?string
    {
        return $this->firstCourse?->courseName;
    }

    public function getCourseApplied2ndAttribute(): ?string
    {
        return $this->secondCourse?->courseName;
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }
}
