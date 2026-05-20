<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dynamic_fields', function (Blueprint $table) {
            $table->string('label_ar')->nullable()->after('label');
            $table->string('label_en')->nullable()->after('label_ar');
        });

        // Copy existing label (Arabic) into label_ar
        DB::table('dynamic_fields')->update([
            'label_ar' => DB::raw('`label`'),
        ]);
    }

    public function down(): void
    {
        Schema::table('dynamic_fields', function (Blueprint $table) {
            $table->dropColumn(['label_ar', 'label_en']);
        });
    }
};
