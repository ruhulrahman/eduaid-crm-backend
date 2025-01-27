<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Social extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

}
