<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LanguageTest extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function parent()
    {
        return $this->belongsTo(LanguageTest::class, 'parent_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

}
