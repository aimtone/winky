<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid', 'protocol', 'address', 'verified_website', 'user_uuid'
    ];

    protected $hidden = [
        'id', 'status', 'created_at', 'updated_at', 'website_verified_at'
    ];
}

