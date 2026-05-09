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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('activetype_id')->constrained('activetypes')->cascadeOnDelete();
            //$table->enum('active', ['Building Materials Supplier', 'Contractor', 'Architect', 'Civil Engineer', 'Plumbing Supplier', 'Electrical Supplier', 'Excavation Company']);
            $table->integer('license_number');
            $table->string('job_name_ar');
            $table->string('job_name_en');
            $table->string('activites');
            $table->string('details');
            $table->foreignId('city_id')->constrained('cities')->cascadeOnDelete();
            $table->string('image')->nullable();
            $table->enum('status',['pending','approved','rejection'])->default('pending');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            //$table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
