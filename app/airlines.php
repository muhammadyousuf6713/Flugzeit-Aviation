<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class airlines extends Authenticatable
{
    protected $table = 'airlines';
    protected $primaryKey = 'id_airlines';
}
