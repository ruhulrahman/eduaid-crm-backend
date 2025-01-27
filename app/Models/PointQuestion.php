<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointQuestion extends Model
{

    protected $guarded = ['id'];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function breakdowns()
    {
        return $this->hasMany(QuestionPointBreakdown::class, 'question_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

}
