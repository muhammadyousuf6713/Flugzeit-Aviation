<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class service_vendor extends Authenticatable
{
    protected $table = 'service_vendors';
    protected $primaryKey = 'id_service_vendors';
}
