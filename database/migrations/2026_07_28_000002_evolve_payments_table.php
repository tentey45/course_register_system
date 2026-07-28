<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Additive evolution of the payments table to match the Payment Link flow.
     *
     * Adds:
     *   - registration_id  (FK to registrations, nullable so old rows are safe)
     *   - payment_method   (human-readable method label, e.g. "ABA PayWay")
     *   - payment_response (raw ABA callback JSON — alongside legacy gateway_response)
     *
     * Nothing is dropped — all existing columns remain untouched.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Link payment to its registration record (created before redirect to ABA).
            $table->foreignId('registration_id')
                ->nullable()
                ->constrained('registrations')
                ->nullOnDelete()
                ->after('id');

            // Human-readable payment method label.
            $table->string('payment_method')->default('ABA PayWay')->after('currency');

            // Raw ABA callback response (stored separately from legacy gateway_response).
            $table->json('payment_response')->nullable()->after('gateway_response');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['registration_id']);
            $table->dropColumn(['registration_id', 'payment_method', 'payment_response']);
        });
    }
};
