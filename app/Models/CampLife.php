<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CampLife extends Model
{
    protected $table = 'campus_life';

    protected $fillable = [
        'name',
        'title',
        'description',
        'image',
        'created_at',
        'updated_at',
    ];
    public function categories(): HasMany
    {
        return $this->hasMany(CampLifeDetail::class, 'campus_life_id', 'id');
    }
}
