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
        Schema::create('order_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->string('price_description')->nullable();
            $table->float('price_description_amount', 2)->nullable();
            $table->integer('letter_count')->nullable();
            $table->float('letter_amount',2)->nullable();
            $table->string('price_description_1')->nullable();
            $table->float('price_description_amount_1', 2)->nullable();
            $table->string('price_description_2')->nullable();
            $table->float('price_description_amount_2', 2)->nullable();
            $table->string('price_description_3')->nullable();
            $table->float('price_description_amount_3', 2)->nullable();
            $table->string('discount_description')->nullable();
            $table->float('discount_amount', 2)->nullable();
            $table->float('total', 2)->nullable();
            $table->string('cemetery_fee_description_1')->nullable();
            $table->float('cemetery_fee_amount_1', 2)->nullable();
            $table->string('cemetery_fee_description_2')->nullable();
            $table->float('cemetery_fee_amount_2', 2)->nullable();
            $table->float('grand_total', 2)->nullable();
            $table->string('deposit_description')->nullable();
            $table->float('deposit_amount', 2)->nullable();
            $table->float('amount_received', 2)->nullable();
            $table->float('balance', 2)->nullable();
            $table->float('net_amount', 2)->nullable();
            $table->float('vat_rate', 2)->nullable();
            $table->float('vat_amount', 2)->nullable();
            $table->float('zero_rated_fee', 2)->nullable();
            $table->float('adjustment', 2)->nullable();
            $table->float('gross_amount', 2)->nullable();
            $table->boolean('is_cost_analysis_print')->nullable()->default(0);
            $table->boolean('is_cost_analysis_trade')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_costs');
    }
};
