<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class URL extends Model
{
    protected $fillable = [
        'user_id',
        'url',
        'short_uri',
        'accessCount'
    ];
}
