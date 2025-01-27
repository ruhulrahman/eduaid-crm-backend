<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadDependent extends Model
{
    protected $table = 'lead_dependent_info';

    protected $guarded = ['id'];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function mobile_country()
    {
        return $this->belongsTo(Country::class, 'mobile_country_id');
    }

}
