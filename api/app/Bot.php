<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid', 'name', 'website_uuid'
    ];

    protected $hidden = [
        'id', 'status', 'created_at', 'updated_at'
    ];
}

