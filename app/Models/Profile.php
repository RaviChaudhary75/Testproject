<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{

    protected $fillable = [
        'name',
        'email',
        'phone',
        'profile_pic',
        'resume',
    ];

    protected $casts = [
        'resume' => 'array',
    ];
}
