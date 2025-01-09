<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class packages extends Authenticatable
{
    protected $table = 'packages';
    protected $primaryKey = 'id_packages';
}
