<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class activateUser extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'uid', 'activation_token'
    ];
}
