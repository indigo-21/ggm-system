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
        Schema::create('order_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->string('free_letters')->nullable();
            $table->boolean('is_burial_society_fees_included')->nullable()->default(0);
            $table->boolean('is_inscription_complete')->nullable()->default(0);
            $table->boolean('is_application_form_sent_to_bs_with_cheque')->nullable()->default(0);
            $table->boolean('is_application_form_sent_to_bs_without_cheque')->nullable()->default(0);
            $table->boolean('is_permit_not_required')->nullable()->default(0);
            $table->boolean('is_insurance')->nullable()->default(0);
            $table->boolean('is_insurance_services')->nullable()->default(0);
            $table->boolean('is_washdown_discussed')->nullable()->default(0);
            $table->boolean('is_paid_by_bacs')->nullable()->default(0);
            $table->boolean('is_full_inscription_received')->nullable()->default(0);
            $table->boolean('is_sent_to_burial_society')->nullable()->default(0);
            $table->boolean('is_received_from_burial_society')->nullable()->default(0);
            $table->boolean('is_order_complete')->nullable()->default(0);
            $table->date('inscription_sent_to_design_team_for_printout')->nullable();
            $table->date('inscription_sent_to_gary_for_printout')->nullable();
            $table->date('received_back_from_design_team')->nullable();
            $table->date('sent_to_customer')->nullable();
            $table->date('back_to_design_team_for_further_alterations')->nullable();
            $table->date('masonart_printout_approved')->nullable();
            $table->boolean('approved_by_burial_society')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_notes');
    }
};
