<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('businesses')->where('status', 'rejection')->update(['status' => 'rejected']);
        DB::table('services')->where('status', 'rejection')->update(['status' => 'rejected']);

        Schema::table('businesses', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->change();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->change();
        });
    }

    public function down(): void
    {
        DB::table('businesses')->where('status', 'rejected')->update(['status' => 'rejection']);
        DB::table('services')->where('status', 'rejected')->update(['status' => 'rejection']);

        Schema::table('businesses', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejection'])->default('pending')->change();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejection'])->default('pending')->change();
        });
    }
};
