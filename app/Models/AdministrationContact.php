<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdministrationContact extends Model
{
    use HasFactory;

    protected $table = 'administration_contact';

    protected $fillable = [
        'ad_id',
        'name',
        'number',
        'email',
    ];

    public function detail()
    {
        return $this->belongsTo(AdministrationDetail::class, 'ad_id', 'id');
    }

    public function authors()
    {
        return $this->hasMany(AdministrationAuthor::class, 'ah_id', 'id');
    }
}
