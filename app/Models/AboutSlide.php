<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutSlide extends Model
{
    // Specify the table name if it doesn't follow Laravel's naming convention
    protected $table = 'about_slides';

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'about_page_id',
        'slide_type',
        'content',
        'order',
    ];

    public function aboutPage()
{
    return $this->belongsTo(AboutPage::class);
}

}
