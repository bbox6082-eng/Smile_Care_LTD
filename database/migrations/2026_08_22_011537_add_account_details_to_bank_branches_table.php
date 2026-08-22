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
        Schema::table('bank_branches', function (Blueprint $table) {
            if (!Schema::hasColumn('bank_branches', 'account_name')) {
                $table->string('account_name')
                    ->nullable()
                    ->after('branch_name');
            }

            if (!Schema::hasColumn('bank_branches', 'account_number')) {
                $table->string('account_number')
                    ->nullable()
                    ->after('account_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_branches', function (Blueprint $table) {
            if (Schema::hasColumn('bank_branches', 'account_number')) {
                $table->dropColumn('account_number');
            }

            if (Schema::hasColumn('bank_branches', 'account_name')) {
                $table->dropColumn('account_name');
            }
        });
    }
};