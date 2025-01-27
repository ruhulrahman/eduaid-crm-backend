<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadVisaHistory extends Model
{

    protected $guarded = ['id'];

    protected $table = "lead_visa_histories";
    protected $appends = ['attachment'];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function visa_type()
    {
        return $this->belongsTo(Status::class, 'visa_type_id');
    }

    public function dependent()
    {
        return $this->belongsTo(LeadDependent::class, 'dependent_id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function getAttachmentAttribute(){

        if($this->media_id){
            return media_url($this->media_id);
        }
    }

}
