<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class inquirytypes extends Authenticatable
{
    protected $table = 'inquirytypes';
    protected $primaryKey = 'type_id';
}
