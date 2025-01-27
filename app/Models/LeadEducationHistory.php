<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadEducationHistory extends Model
{

    protected $guarded = ['id'];

    protected $table = "lead_education_histories";

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }
    public function institute_country()
    {
        return $this->belongsTo(Country::class, 'institute_country_id');
    }

    public function result_type()
    {
        return $this->belongsTo(Status::class, 'result_type_id');
    }

    public function course_level()
    {
        return $this->belongsTo(CourseLevel::class, 'course_level_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'institute_country_id');
    }

    public function dependent()
    {
        return $this->belongsTo(LeadDependent::class, 'dependent_id');
    }

}
