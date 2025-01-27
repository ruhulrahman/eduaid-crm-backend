<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionPointBreakdown extends Model
{

    protected $guarded = ['id'];

    public function question()
    {
        return $this->belongsTo(PointQuestion::class, 'question_id');
    }

}
