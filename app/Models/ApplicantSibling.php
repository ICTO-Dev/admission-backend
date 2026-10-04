<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicantSibling extends Model
{
    use HasFactory;

    protected $table = 'applicant_siblings';

    protected $fillable = [
        'application_id',
        'full_name',
        'age',
        'sex',
        'civil_status',
        'educational_attainment',
    ];

    protected $casts = [
        'age' => 'integer',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
}
