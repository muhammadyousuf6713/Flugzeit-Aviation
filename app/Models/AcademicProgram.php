<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicProgram extends Model
{
    protected $table = 'ap_header';

    protected $fillable = [
        'name',
        'title',
        'description',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the categories associated with the academic program.
     */
    public function categories(): HasMany
    {
        return $this->hasMany(AcademicProgramCategory::class, 'aph_id', 'id');
    }
}
