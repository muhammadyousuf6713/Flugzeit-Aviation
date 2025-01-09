<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class departments extends Authenticatable
{
    protected $table = 'departments';
    protected $primaryKey = 'id_departments';
}
