<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentPlanDelivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_plan_id',
        'upper_delivered',
        'lower_delivered',
        'paid_amount',
        'delivery_date',
        'created_by',
    ];

    protected $casts = [
        'upper_delivered' => 'integer',
        'lower_delivered' => 'integer',
        'paid_amount' => 'decimal:2',
        'delivery_date' => 'date',
    ];

    public function paymentPlan()
    {
        return $this->belongsTo(
            PaymentPlan::class,
            'payment_plan_id'
        );
    }
}

