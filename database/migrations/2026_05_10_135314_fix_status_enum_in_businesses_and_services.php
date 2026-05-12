<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("UPDATE `businesses` SET `status` = 'rejected' WHERE `status` = 'rejection'");
        DB::statement("ALTER TABLE `businesses` MODIFY `status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'");

        DB::statement("UPDATE `services` SET `status` = 'rejected' WHERE `status` = 'rejection'");
        DB::statement("ALTER TABLE `services` MODIFY `status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("UPDATE `businesses` SET `status` = 'rejection' WHERE `status` = 'rejected'");
        DB::statement("ALTER TABLE `businesses` MODIFY `status` ENUM('pending','approved','rejection') NOT NULL DEFAULT 'pending'");

        DB::statement("UPDATE `services` SET `status` = 'rejection' WHERE `status` = 'rejected'");
        DB::statement("ALTER TABLE `services` MODIFY `status` ENUM('pending','approved','rejection') NOT NULL DEFAULT 'pending'");
    }
};
