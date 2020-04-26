<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan_Planitem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid', 'plan_id', 'planitem_id', 'value', 'status'
    ];
}
