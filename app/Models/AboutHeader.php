<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutHeader extends Model
{
    use HasFactory;
    protected $table = 'about_header';

    protected $fillable = [
        'name',
        'image',
        'status',
    ];

    // Relationship with AboutDetail
    public function details()
    {
        return $this->hasMany(AboutDetail::class, 'about_header_id');
    }
}
