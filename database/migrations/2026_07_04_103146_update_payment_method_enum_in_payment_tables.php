<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE payment_plans
            MODIFY payment_method
            ENUM('cash','card','bank_transfer','mobile_banking')
            DEFAULT 'cash'
        ");

        DB::statement("
            ALTER TABLE payment_plan_payments
            MODIFY payment_method
            ENUM('cash','card','bank_transfer','mobile_banking')
            DEFAULT 'cash'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE payment_plans
            MODIFY payment_method
            ENUM('cash','card','bank_transfer')
            DEFAULT 'cash'
        ");

        DB::statement("
            ALTER TABLE payment_plan_payments
            MODIFY payment_method
            ENUM('cash','card','bank_transfer')
            DEFAULT 'cash'
        ");
    }
};