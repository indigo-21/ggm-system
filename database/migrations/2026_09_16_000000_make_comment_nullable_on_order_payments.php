<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Makes the payment comment optional. Altering the column to nullable does
     * not touch existing rows, so current payment data is preserved.
     */
    public function up(): void
    {
        Schema::table('order_payments', function (Blueprint $table) {
            $table->string('comment')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Note: reverting to NOT NULL would fail if any rows have a NULL comment,
     * so we backfill NULLs to an empty string first to keep the down path safe.
     */
    public function down(): void
    {
        DB::table('order_payments')->whereNull('comment')->update(['comment' => '']);

        Schema::table('order_payments', function (Blueprint $table) {
            $table->string('comment')->nullable(false)->change();
        });
    }
};
