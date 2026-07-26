<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_branches', function (Blueprint $table) {

            $table->id();

            $table->foreignId('bank_id')
                  ->constrained('banks')
                  ->cascadeOnDelete();

            $table->string('branch_name');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_branches');
    }
};