<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class cities extends Authenticatable
{
    protected $table = 'cities';
    protected $primaryKey = 'id';
    public static function  getCityName($id)
    {
        $city_name = cities::where('id', $id)->select('name')->first();
        return  $city_name->name;
    }
}
