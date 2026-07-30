<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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

    public function paymentPlan()
    {
        return $this->belongsTo(
            PaymentPlan::class,
            'payment_plan_id'
        );
    }
    

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
