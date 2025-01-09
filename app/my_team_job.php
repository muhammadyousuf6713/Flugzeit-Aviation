<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class my_team_job extends Model
{
    protected $primaryKey="id_my_team_jobs";

    public function get_inquiry()
    {
        return $this->belongsTo(inquiry::class,'inquiry_id','id_inquiry');
    }
}
