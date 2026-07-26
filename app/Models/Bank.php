<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    protected $fillable = [
        'bank_name',
    ];

    public function branches(): HasMany
    {
        return $this->hasMany(BankBranch::class);
    }
}