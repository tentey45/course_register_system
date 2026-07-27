<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Payments are created BEFORE a Registration exists. A Registration row
     * is only created once a payment's status becomes "paid". This keeps
     * the existing `registrations` table/enum completely untouched.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('method'); // aba_payway | bakong
            $table->decimal('amount', 8, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('transaction_id')->unique();
            $table->string('status')->default('pending'); // pending | paid | failed | cancelled
            $table->text('qr_string')->nullable();
            $table->json('gateway_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};