<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    protected $table = 'region';

    public $timestamps = false;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'longitude',
        'latitude',
        'office',
    ];

    public function provinces(): HasMany
    {
        return $this->hasMany(Province::class, 'region_id');
    }
}
