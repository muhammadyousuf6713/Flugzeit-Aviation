<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Sofa\Eloquence\Eloquence;

class inquiry extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $primaryKey = 'id_inquiry';
    protected $table = 'inquiry';
    public $timestamps = true;

    protected $fillable = [
        'customer_id',
        'inquiry_category',
        'services',
        'sub_services',
        'services_sub_services',
        'remarks',
        'saleperson',
        'inquiry_type',
        'created_by',
        'status'  // if you're setting it too
    ];

    // public function get_customer()
    // {
    //     return $this->belongsTo(customer::class,'customer_id','id_customers');
    // }

    public function customer()
    {
        return $this->belongsTo(customer::class, 'customer_id', 'id_customers');
    }

    // In Inquiry.php model

    public function salesReference()
    {
        return $this->belongsTo(sales_reference::class, 'sales_reference');
    }

    public function salesPerson()
    {
        return $this->belongsTo(User::class, 'saleperson');
    }

    public function inquiryType()
    {
        return $this->belongsTo(inquirytypes::class, 'inquiry_type', 'type_id');
    }

    // In Inquiry.php model
    public function followups()
    {
        return $this->hasMany(followup_remark::class, 'inquiry_id');
    }

    public function latestFollowup()
    {
        return $this
            ->hasOne(followup_remark::class, 'inquiry_id', 'id_inquiry')
            ->latest('id_followup_remarks');
    }

    public function remarks()
    {
        return $this->hasMany(remarks::class, 'inquiry_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function followupRemarks()
    {
        return $this
            ->hasMany(followup_remark::class, 'inquiry_id')
            ->latest()
            ->limit(5);  // Only load last 5 remarks
    }
}
