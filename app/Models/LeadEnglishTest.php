<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadEnglishTest extends Model
{

    protected $guarded = ['id'];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function language_test()
    {
        return $this->belongsTo(LanguageTest::class, 'language_test_id');
    }

    public function course_level()
    {
        return $this->belongsTo(CourseLevel::class, 'course_level_id');
    }

    public function level()
    {
        return $this->belongsTo(CourseLevel::class, 'level_id');
    }

    public function dependent()
    {
        return $this->belongsTo(LeadDependent::class, 'dependent_id');
    }

}
