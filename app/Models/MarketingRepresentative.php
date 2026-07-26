<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingRepresentative extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'blood_group',
        'address',
        'emergency_contact',
    ];
}
