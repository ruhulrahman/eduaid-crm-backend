<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadQuestionPoint extends Model
{

    protected $guarded = ['id'];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function question()
    {
        return $this->belongsTo(PointQuestion::class, 'question_id');
    }

    public function qp_breakdown()
    {
        return $this->belongsTo(QuestionPointBreakdown::class, 'qp_breakdown_id');
    }

}
