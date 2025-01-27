<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadEmergencyContact extends Model
{

    protected $table = 'lead_emergency_contacts';

    protected $guarded = ['id'];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function dependent()
    {
        return $this->belongsTo(LeadDependent::class, 'dependent_id');
    }

    public function mobile_country()
    {
        return $this->belongsTo(Country::class, 'mobile_country_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

}
