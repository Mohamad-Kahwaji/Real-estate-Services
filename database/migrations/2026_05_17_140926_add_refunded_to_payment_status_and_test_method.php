<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid')->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->enum('payment_method', ['bank_transfer', 'stripe', 'paypal', 'test'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid')->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->enum('payment_method', ['bank_transfer', 'stripe', 'paypal'])->change();
        });
    }
};
