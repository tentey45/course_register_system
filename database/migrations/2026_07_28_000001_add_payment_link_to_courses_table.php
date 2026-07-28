<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add payment_link column to courses table.
     * Stores the pre-created ABA PayWay sandbox payment link URL
     * assigned to each course (e.g. https://link-sandbox.payway.com.kh/Dz80706N).
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('payment_link', 500)->nullable()->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('payment_link');
        });
    }
};
