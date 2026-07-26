<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_plan_deliveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_plan_id');
            $table->unsignedInteger('upper_delivered')->default(0);
            $table->unsignedInteger('lower_delivered')->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->date('delivery_date');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('payment_plan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_plan_deliveries');
    }
};

