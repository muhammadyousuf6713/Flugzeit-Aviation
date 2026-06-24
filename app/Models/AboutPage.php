<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPage extends Model
{
    protected $table = 'about_pages';

    protected $fillable = [
        'sidebar_items',
        'vision',
        'mission',
        'core_values',
        'hec_recognition',
        'future_aspiration',
        'chancellor_message',
        'vice_chancellor_message',
        'project_head_message',
    ];

    public function slides()
{
    return $this->hasMany(AboutSlide::class);
}
}
