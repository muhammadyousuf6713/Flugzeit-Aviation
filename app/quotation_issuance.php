<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class quotation_issuance extends Model
{
    protected $primaryKey = "id_quotation_issuance";
    public function get_quotation()
    {
        return $this->belongsTo(quotation::class, 'quotation_id', 'id_quotations');
    }
    public function get_user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function get_user_assign()
    {
        return $this->belongsTo(User::class, 'assign_to', 'id');
    }
}
