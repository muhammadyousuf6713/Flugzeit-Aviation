<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class hotel_inventory extends Authenticatable
{
    protected $table = 'hotel_inventory';
    protected $primaryKey = 'id_hotel_inventory';
}
