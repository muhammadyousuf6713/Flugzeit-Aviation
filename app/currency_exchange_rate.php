<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class currency_exchange_rate extends Model
{
    protected $table = 'currency_exchange_rates';
    protected $primaryKey = 'id_currency_exchange_rates';
}
