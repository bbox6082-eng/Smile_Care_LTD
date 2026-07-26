<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->unique('name');
        });

        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
            $table->unique(['region_id', 'name']);
        });

        Schema::create('territories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
            $table->unique(['area_id', 'name']);
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->string('mobile_number')->nullable()->after('name');
            $table->string('email')->nullable()->after('mobile_number');
            $table->text('note')->nullable()->after('email');
            $table->foreignId('territory_id')->nullable()->after('note')->constrained()->nullOnDelete();
        });

        if (Schema::hasColumn('doctors', 'contact')) {
            foreach (DB::table('doctors')->get() as $row) {
                if (! empty($row->contact)) {
                    DB::table('doctors')->where('id', $row->id)->update(['mobile_number' => $row->contact]);
                }
            }
        }

        Schema::table('doctors', function (Blueprint $table) {
            if (Schema::hasColumn('doctors', 'contact')) {
                $table->dropColumn('contact');
            }
            if (Schema::hasColumn('doctors', 'marketing_representative_name')) {
                $table->dropColumn('marketing_representative_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->string('contact')->nullable()->after('name');
            $table->string('marketing_representative_name')->nullable()->after('chamber_address');
        });

        if (Schema::hasColumn('doctors', 'mobile_number')) {
            foreach (DB::table('doctors')->get() as $row) {
                if (! empty($row->mobile_number)) {
                    DB::table('doctors')->where('id', $row->id)->update(['contact' => $row->mobile_number]);
                }
            }
        }

        Schema::table('doctors', function (Blueprint $table) {
            $table->dropForeign(['territory_id']);
            $table->dropColumn(['territory_id', 'email', 'note', 'mobile_number']);
        });

        Schema::dropIfExists('territories');
        Schema::dropIfExists('areas');
        Schema::dropIfExists('regions');
    }
};
