<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_notes', function (Blueprint $table) {
            $table->dateTime("is_application_form_sent_to_bs_with_cheque_timestamp")->after("is_application_form_sent_to_bs_with_cheque")->nullable();
            $table->dateTime("is_application_form_sent_to_bs_without_cheque_timestamp")->after("is_application_form_sent_to_bs_without_cheque")->nullable();
            $table->dateTime("is_paid_by_bacs_timestamp")->after("is_paid_by_bacs")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_notes', function (Blueprint $table) {
            $table->dropColumn("is_application_form_sent_to_bs_with_cheque_timestamp");
            $table->dropColumn("is_application_form_sent_to_bs_without_cheque_timestamp");
            $table->dropColumn("is_paid_by_bacs_timestamp");
        });
    }
};
