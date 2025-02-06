<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class customer extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'id_customers';
    protected $table = 'customers';

    public function salePerson()
    {
        return $this->belongsTo(User::class, 'sale_person');
    }
    public function city()
    {
        return $this->belongsTo(cities::class);
    }
}
