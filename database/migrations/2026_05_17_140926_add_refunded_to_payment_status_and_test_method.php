<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE service_requests MODIFY payment_status ENUM('unpaid','paid','refunded') NOT NULL DEFAULT 'unpaid'");
        DB::statement("ALTER TABLE payments MODIFY payment_method ENUM('bank_transfer','stripe','paypal','test') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE service_requests MODIFY payment_status ENUM('unpaid','paid') NOT NULL DEFAULT 'unpaid'");
        DB::statement("ALTER TABLE payments MODIFY payment_method ENUM('bank_transfer','stripe','paypal') NOT NULL");
    }
};
