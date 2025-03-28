<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdministrationHeader extends Model
{
    use HasFactory;

    protected $table = 'administration_header';

    protected $fillable = [
        'name',
        'status',
    ];

    public function details()
    {
        return $this->hasMany(AdministrationDetail::class, 'ah_id', 'id');
    }

    public function authors()
    {
        return $this->hasMany(AdministrationAuthor::class, 'ah_id', 'id');
    }
}
