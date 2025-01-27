<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{

    protected $guarded = ['id'];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function mobile_country()
    {
        return $this->belongsTo(Country::class, 'mobile_country_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function dependents()
    {
        return $this->hasMany(LeadDependent::class, 'lead_id');
    }

    public function socials()
    {
        return $this->hasMany(LeadSocial::class, 'lead_id');
    }

    public function candidate_socials () {
        return $this->hasMany(LeadSocial::class, 'lead_id')->whereNULL('dependent_id');
    }

    public function dependent_socials () {
        return $this->hasMany(LeadSocial::class, 'lead_id')->whereNotNULL('dependent_id');
    }

    public function contacts()
    {
        return $this->hasMany(LeadContact::class, 'lead_id');
    }

    public function candidate_contacts () {
        return $this->hasMany(LeadContact::class, 'lead_id')->whereNULL('dependent_id');
    }

    public function dependent_contacts () {
        return $this->hasMany(LeadContact::class, 'lead_id')->whereNotNULL('dependent_id');
    }

    public function emergency_contacts () {
        return $this->hasMany(LeadEmergencyContact::class, 'lead_id');
    }

    public function candidate_emergency_contacts () {
        return $this->hasMany(LeadEmergencyContact::class, 'lead_id')->whereNULL('dependent_id');
    }

    public function dependent_emergency_contacts () {
        return $this->hasMany(LeadEmergencyContact::class, 'lead_id')->whereNotNULL('dependent_id');
    }

    public function employment_histories () {
        return $this->hasMany(LeadEmploymentHistory::class, 'lead_id');
    }

    public function candidate_employment_histories () {
        return $this->hasMany(LeadEmploymentHistory::class, 'lead_id')->whereNULL('dependent_id');
    }

    public function dependent_employment_histories () {
        return $this->hasMany(LeadEmploymentHistory::class, 'lead_id')->whereNotNULL('dependent_id');
    }

    public function services () {
        return $this->hasMany(LeadService::class, 'lead_id');
    }

    public function visa_histories () {
        return $this->hasMany(LeadVisaHistory::class, 'lead_id');
    }

    public function candidate_visa_histories () {
        return $this->hasMany(LeadVisaHistory::class, 'lead_id')->whereNULL('dependent_id');
    }

    public function dependent_visa_histories () {
        return $this->hasMany(LeadVisaHistory::class, 'lead_id')->whereNotNULL('dependent_id');
    }

    public function education_histories () {
        return $this->hasMany(LeadEducationHistory::class, 'lead_id');
    }

    public function candidate_education_histories () {
        return $this->hasMany(LeadEducationHistory::class, 'lead_id')->whereNULL('dependent_id');
    }

    public function dependent_education_histories () {
        return $this->hasMany(LeadEducationHistory::class, 'lead_id')->whereNotNULL('dependent_id');
    }

    public function question_points () {
        return $this->hasMany(LeadQuestionPoint::class, 'lead_id');
    }

    public function english_tests () {
        return $this->hasMany(LeadEnglishTest::class, 'lead_id');
    }

    public function candidate_english_tests () {
        return $this->hasMany(LeadEnglishTest::class, 'lead_id')->whereNULL('dependent_id');
    }

    public function dependent_english_tests () {
        return $this->hasMany(LeadEnglishTest::class, 'lead_id')->whereNotNULL('dependent_id');
    }

    public function english_test_results () {
        return $this->hasMany(LeadEnglishTestResult::class, 'lead_id');
    }

    public function candidate_english_test_results () {
        return $this->hasMany(LeadEnglishTestResult::class, 'lead_id')->whereNULL('dependent_id');
    }

    public function dependent_english_test_results () {
        return $this->hasMany(LeadEnglishTestResult::class, 'lead_id')->whereNotNULL('dependent_id');
    }

    public function lead_payments () {
        return $this->hasMany(LeadPayment::class, 'lead_id')->orderBy('sl_no', 'asc');
    }


}
