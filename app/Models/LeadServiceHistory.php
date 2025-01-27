<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadServiceHistory extends Model
{

    protected $guarded = ['id'];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function service_status()
    {
        return $this->belongsTo(Status::class, 'service_status_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

}
