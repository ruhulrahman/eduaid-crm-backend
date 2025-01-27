<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadEnglishTestResult extends Model
{

    protected $guarded = ['id'];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function dependent()
    {
        return $this->belongsTo(LeadDependent::class, 'dependent_id');
    }

    public function english_language_test()
    {
        return $this->belongsTo(LeadEnglishTest::class, 'lead_english_test_id');
    }

    public function language_test()
    {
        return $this->belongsTo(LanguageTest::class, 'id');
    }

    public function child_language_test()
    {
        return $this->belongsTo(LanguageTest::class, 'child_language_test_id');
    }

    public function course_level()
    {
        return $this->belongsTo(CourseLevel::class, 'course_level_id');
    }

}
