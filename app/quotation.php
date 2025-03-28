<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\quotation_issuance;

class quotation extends Model
{
    protected $primaryKey = "id_quotations";

    public function get_issuance()
    {
        return $this->belongsTo(quotation_issuance::class, 'id_quotations','quotation_id');
    }
    public function get_inquiry()
    {
        return $this->belongsTo(inquiry::class, 'inquiry_id','id_inquiry');
    }
    public function getIssuancedataAttribute($value)
    {

        $get_issuance=quotation_issuance::where('quotation_id',$this->id_quotations)->get();

       return $value;
        // return $this->belongsTo(quotation_issuance::class, 'id_quotations','quotation_id');
    }
}
