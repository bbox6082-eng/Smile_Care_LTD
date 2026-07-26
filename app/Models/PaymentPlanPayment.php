<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentPlanPayment extends Model
{
    use HasFactory;

    protected $fillable = [
    'payment_plan_id',
    'amount',
    'payment_date',
    'payment_method',

    'bank_name',
    'branch_name',
    'account_name',
    'account_number',

    'mobile_provider',
    'transaction_id',

    'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];
}
