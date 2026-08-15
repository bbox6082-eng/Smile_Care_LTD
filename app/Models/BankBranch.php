<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankBranch extends Model
{
    protected $fillable = [
        'bank_id',
        'branch_name',
        'account_name',
        'account_number',
    ];

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }
}