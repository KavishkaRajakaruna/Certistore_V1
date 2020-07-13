<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class passwordReset extends Model
{
    protected $fillable=[
        'email' , 'token'
    ];
}
