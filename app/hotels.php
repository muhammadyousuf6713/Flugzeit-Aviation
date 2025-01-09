<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class hotels extends Authenticatable
{
    protected $table = 'hotels';
    protected $primaryKey = 'id_hotels';

    public function get_hotel_details()
    {
        return $this->belongsTo(hotel_details::class, 'id_hotels', 'hotel_id');
    }
}
