<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadPayment extends Model
{

    protected $guarded = ['id'];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

}
