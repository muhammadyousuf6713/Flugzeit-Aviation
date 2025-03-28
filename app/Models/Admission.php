<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admission extends Model
{
    protected $table = 'admission_header';

    protected $fillable = [
        'name',
        'title',
        'description',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the Admission Category associated with the Admission.
     */
    public function categories(): HasMany
    {
        return $this->hasMany(AdmissionCategory::class, 'ah_id', 'id');
    }
}
