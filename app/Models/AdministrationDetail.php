<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdministrationDetail extends Model
{
    use HasFactory;

    protected $table = 'administration_detail';

    protected $fillable = [
        'ah_id',
        'title',
        'description',
        'status',
    ];

    public function header()
    {
        return $this->belongsTo(AdministrationHeader::class, 'ah_id', 'id');
    }

    public function contacts()
    {
        return $this->hasMany(AdministrationContact::class, 'ad_id', 'id');
    }
}
