<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = [
        'id',
        'courseName',
        'campus_id',
        'status',
        'college',
        'is_open_program',
        'date_added',
    ];

    protected $casts = [
        'is_open_program' => 'boolean',
        'status' => 'integer',
        'campus_id' => 'integer',
        'date_added' => 'datetime',
    ];

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class, 'campus_id');
    }
}
