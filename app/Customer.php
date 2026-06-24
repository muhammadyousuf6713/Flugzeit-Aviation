<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class customer extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'id_customers';
    public $incrementing = true;
    public $timestamps = true;
    protected $keyType = 'int';
    protected $table = 'customers';
    protected $fillable = [
        'customer_name',
        'customer_cell',
        'customer_email',
        'customer_type',
        'sale_person',
        'created_by'
    ];

    public function salePerson()
    {
        return $this->belongsTo(User::class, 'sale_person');
    }
    public function city()
    {
        return $this->belongsTo(cities::class);
    }
}
