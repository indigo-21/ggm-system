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
        Schema::create('order_new_memorials', function (Blueprint $table) {
            $table->id();
            $table->foreignId("order_id")->constrained("orders");
            $table->boolean("for_fixing")->default(0);
            $table->date("fixing_date")->nullable();
            $table->integer("fixing_status")->nullable()->comment("1=cash|2=cheque|3=credit_card|4=bank_transfer|5=debit_card");
            $table->integer("view_location")->nullable()->comment("1=by_photo|2=clayhall|3=edgeware");
            $table->integer("view_status")->nullable()->comment("1=factory|2=on_route|3=showroom|4=photo_sent");
            $table->date("view_date")->nullable();
            $table->longText("description")->nullable();
            $table->longText("issue")->nullable();
            $table->boolean("is_customer_approved")->default(0);
            $table->boolean("is_inscription_factory_approved")->default(0);
            $table->datetime("inscription_factory_approved_timestamp")->nullable();
            $table->boolean("is_burial_society_approved")->default(0);
            $table->boolean("is_email_sent")->default(0);
            $table->boolean("is_email_sent_post")->default(0);
            $table->datetime("email_sent_timestamp")->nullable();
            $table->boolean("is_permit_back")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_new_memorials');
    }
};
