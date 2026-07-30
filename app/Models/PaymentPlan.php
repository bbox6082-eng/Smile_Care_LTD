<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'predict3d_id',
        'total_amount',
        'payment_method',
        'is_installment',
        'next_payment_date',
        'remaining_amount',
        'is_closed',
        'created_by',
    ];

    protected $casts = [
        'total_amount'      => 'decimal:2',
        'remaining_amount'  => 'decimal:2',
        'is_installment'    => 'boolean',
        'is_closed'         => 'boolean',
        'next_payment_date' => 'date',
    ];

    /**
     * Patient associated with this payment plan.
     */
    public function patient()
    {
        return $this->belongsTo(
            Patient::class,
            'predict3d_id',
            'Predict3DId'
        );
    }

    /**
     * Installment payments.
     */
    public function payments()
    {
        return $this->hasMany(
            PaymentPlanPayment::class,
            'payment_plan_id'
        );
    }

    /**
     * Aligner deliveries.
     */
    public function deliveries()
    {
        return $this->hasMany(
            PaymentPlanDelivery::class,
            'payment_plan_id'
        );
    }
}