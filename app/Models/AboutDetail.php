<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutDetail extends Model
{
    use HasFactory;
    protected $table    = 'about_details';
    protected $fillable = [
        'about_header_id',
        'image',
        'title',
        'name',
        'detail',
        'description',
        'from_date',
        'to_date',
    ];

    // Relationship with AboutHeader
    public function header()
    {
        return $this->belongsTo(AboutHeader::class, 'about_header_id');
    }
}
