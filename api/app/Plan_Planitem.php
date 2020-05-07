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
        'plan_uuid', 'planitem_uuid', 'value', 'status'
    ];

    protected $hidden = [
        'id', 'status', 'created_at', 'updated_at'
    ];
}
