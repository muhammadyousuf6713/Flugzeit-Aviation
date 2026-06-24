<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademicProgramCategory extends Model
{
    protected $table = 'ap_category';

    protected $fillable = [
        'aph_id',
        'name',
        'description',
        'approach',
        'enriching_experience',
        'ultimate_goal',
        'eligibility',
        'career',
        'cta',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'eligibility' => 'array',
        'cta'         => 'array',
    ];
    /**
     * Get the academic program that owns the category.
     */
    public function academicProgram(): BelongsTo
    {
        return $this->belongsTo(academicProgram::class, 'aph_id', 'id');
    }

}
