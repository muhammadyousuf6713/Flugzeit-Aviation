<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampLifeDetail extends Model
{
    protected $table = 'campus_life_detail';

    protected $fillable = [
        'campus_life_id',
        'name',
        'title',
        'description',
        'image',
        'created_at',
        'updated_at',
    ];
    public function academicProgram(): BelongsTo
    {
        return $this->belongsTo(CampLife::class, 'campus_life_id', 'id');
    }
}
