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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string("title")->nullable();
            $table->string("firstname")->nullable();
            $table->string("lastname");
            $table->string("salutation")->nullable();
            $table->string("address_one")->nullable();
            $table->string("address_two")->nullable();
            $table->string("city_county")->nullable();
            $table->string("postcode")->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('customer_contacts', function (Blueprint $table) {
            $table->id();
            $table->string("customer_id")->constrained("customers");
            $table->integer("contact_type")->comment("1 = email, 2 = Mobile no., 3 = Tel No.");
            $table->string("contact_value");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
        Schema::dropIfExists('customer_contacts');
    }
};
