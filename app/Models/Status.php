<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{

    protected $guarded = ['id'];
    protected $appends = ['parsed_data'];

    public function group()
    {
        return $this->belongsTo(StatusGroup::class, 'status_group_id');
    }

    public function roles()
    {
        return $this->hasMany(Role::class, 'user_type_id');
    }

    public function getParsedDataAttribute(){

        return json_decode($this->data);

    }

}
