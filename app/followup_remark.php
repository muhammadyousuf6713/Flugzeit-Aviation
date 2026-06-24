<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class followup_remark extends Model
{
    protected $table = 'followup_remarks';
    protected $primaryKey = 'id_followup_remarks';

    public function get_followup_remarks()
    {
        return $this->BelongsTo(followup_remark::class, 'id_followups', 'followup_id');
    }

    public function inquiry()
    {
        return $this->belongsTo(inquiry::class, 'inquiry_id', 'id_inquiry');
    }

    public function followupType()
    {
        return $this->belongsTo(follow_up_type::class, 'followup_type', 'id_follow_up_types');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
