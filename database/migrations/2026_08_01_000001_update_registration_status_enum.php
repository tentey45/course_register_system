<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // Modify the status enum to include pending_payment and cancelled values.
        // MySQL requires a raw statement for enum alteration.
        DB::statement("ALTER TABLE `registrations`
            MODIFY `status` ENUM('registered','pending_payment','cancelled')
            NOT NULL DEFAULT 'registered'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // Revert to the original enum (registered, dropped)
        DB::statement("ALTER TABLE `registrations`
            MODIFY `status` ENUM('registered','dropped')
            NOT NULL DEFAULT 'registered'");
    }
};
