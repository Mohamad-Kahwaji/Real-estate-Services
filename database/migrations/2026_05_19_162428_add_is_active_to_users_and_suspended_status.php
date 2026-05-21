<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });

        DB::statement("ALTER TABLE `businesses` MODIFY `status` ENUM('pending','approved','rejected','suspended') NOT NULL DEFAULT 'pending'");
        DB::statement("ALTER TABLE `services`   MODIFY `status` ENUM('pending','approved','rejected','suspended') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        DB::statement("UPDATE `businesses` SET `status` = 'rejected' WHERE `status` = 'suspended'");
        DB::statement("ALTER TABLE `businesses` MODIFY `status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'");

        DB::statement("UPDATE `services` SET `status` = 'rejected' WHERE `status` = 'suspended'");
        DB::statement("ALTER TABLE `services`   MODIFY `status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'");
    }
};
