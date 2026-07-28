<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('courses')
            ->where('course_code', 'CS201')
            ->update([
                'price' => 100.00,
                'payment_link' => 'https://link-sandbox.payway.com.kh/OR80707g',
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('courses')
            ->where('course_code', 'CS201')
            ->update([
                'payment_link' => null,
            ]);
    }
};
