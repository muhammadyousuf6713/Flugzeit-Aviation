<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionCategory extends Model
{
    protected $table = 'admission_category';

    protected $fillable = [
        'ah_id',
        'name',
        'description',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the Admission that owns the category.
     */
    public function academicProgram(): BelongsTo
    {
        return $this->belongsTo(Admission::class, 'ah_id', 'id');
    }
}
