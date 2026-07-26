<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payment_plan_payments', function (Blueprint $table) {

            // Bank Transfer
            $table->string('bank_name')->nullable()->after('payment_method');
            $table->string('branch_name')->nullable()->after('bank_name');
            $table->string('account_name')->nullable()->after('branch_name');
            $table->string('account_number')->nullable()->after('account_name');

            // Mobile Banking
            $table->string('mobile_provider')->nullable()->after('account_number');
            $table->string('transaction_id')->nullable()->after('mobile_provider');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_plan_payments', function (Blueprint $table) {

            $table->dropColumn([
                'bank_name',
                'branch_name',
                'account_name',
                'account_number',
                'mobile_provider',
                'transaction_id'
            ]);

        });
    }
};