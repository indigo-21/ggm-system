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
        Schema::create('order_cost_additionals', function (Blueprint $table) {
            $table->id();
            $table->foreignId("order_cost_id")->constrained("order_costs", "id");
            $table->string("description");
            $table->float("amount",2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_cost_additionals');
    }
};
