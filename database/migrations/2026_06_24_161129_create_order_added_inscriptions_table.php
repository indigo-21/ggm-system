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
        Schema::create('order_added_inscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId("order_id")->constrained("orders");
            $table->date("schedule_date")->nullable();
            $table->integer("schedule_status")->default(0)->comment("0=assigned|1=completed|2=tobedone");
            $table->integer("payment_status")->default(0)->comment("0=unpaid|1=paid|2=part_paid");
            $table->boolean("is_permit_back")->default(0);
            $table->boolean("is_customer_approved")->default(0);
            $table->boolean("is_burial_society_approved")->default(0);
            $table->boolean("is_inscription_factory_approved")->default(0);
            $table->datetime("inscription_factory_approved_timestamp")->nullable();
            $table->longText("details")->nullable();
            $table->longText("extras")->nullable();
            $table->longText("issue")->nullable();
            $table->longText("letter_cutter_name")->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_added_inscriptions');
    }
};
