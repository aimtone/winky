<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Planitem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'status'
    ];

    protected $hidden = [
        'id', 'status', 'created_at', 'updated_at'
    ];
  
}
