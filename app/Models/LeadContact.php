<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadContact extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }
    public function dependent()
    {
        return $this->belongsTo(LeadDependent::class, 'dependent_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

}
