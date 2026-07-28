<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Update the registrations.status enum to support the Payment Link flow.
     *
     * Old values: registered | dropped
     * New values: pending_payment | registered | cancelled
     *
     * Uses a raw ALTER TABLE because Laravel's Blueprint doesn't support
     * modifying an existing enum column cleanly without doctrine/dbal.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE registrations
            MODIFY COLUMN status
            ENUM('pending_payment', 'registered', 'cancelled')
            NOT NULL DEFAULT 'pending_payment'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE registrations
            MODIFY COLUMN status
            ENUM('registered', 'dropped')
            NOT NULL DEFAULT 'registered'
        ");
    }
};
