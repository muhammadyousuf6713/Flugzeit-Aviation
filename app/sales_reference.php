<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class sales_reference extends Authenticatable
{
    protected $table = 'sales_reference';
    protected $primaryKey = 'type_id';
}
