<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdministrationAuthor extends Model
{
    use HasFactory;

    protected $table = 'administration_authors';

    protected $fillable = [
        'ah_id',
        'image',
        'name',
        'about',
        'number',
        'email',
        'address',
        'description',
        'depart_name',
    ];

    public function contact()
    {
        return $this->belongsTo(AdministrationContact::class, 'ah_id', 'id');
    }
}
