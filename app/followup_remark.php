<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class followup_remark extends Model
{

    protected $table = 'followup_remarks';
    protected $primaryKey = 'id_followup_remarks';
    
    public function get_followup_remarks()
    {
        return $this->BelongsTo(followup_remark::class,'id_followups' ,'followup_id');
    }
}
