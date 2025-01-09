<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class quotations_detail extends Model
{

    protected $primaryKey='id_quotation_details';
    public function get_payment()
    {
        return $this->belongsTo(payment::class, 'id_quotation_details', 'quotation_detail_id');
    }
}
