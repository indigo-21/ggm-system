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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_type_id')->constrained();
            $table->foreignId('location_id')->constrained();
            $table->foreignId('customer_id')->constrained();
            $table->string('deceased_name')->nullable();
            $table->dateTime('date_of_death')->nullable();
            $table->date('consecration_date')->nullable();
            $table->foreignId('cemetery_id')->nullable()->constrained();
            $table->boolean('is_tba')->default(0);
            $table->boolean('is_approx')->default(0);
            $table->boolean('is_asap')->default(0);
            $table->date('fixing_date')->nullable();
            $table->foreignId('burial_society_organization_id')->nullable()->constrained();
            $table->string('grave_number')->nullable();
            $table->date('grave_number_checked')->nullable();
            $table->foreignId('grave_space_id')->nullable()->constrained();
            $table->string('design_headstone')->nullable();
            $table->string('material')->nullable();
            $table->string('material_colour')->nullable();
            $table->string('size')->nullable();
            $table->string('base_ledger')->nullable();
            $table->string('letter_type')->nullable();
            $table->string('accessory')->nullable();
            $table->string('accessory_colour')->nullable();
            $table->string('kerb_riser')->nullable();
            $table->string('issue')->nullable();
            $table->string('special_instruction')->nullable();
            $table->string('customer_notes')->nullable();
            $table->string('additional_notes')->nullable();
            $table->string('invoice_no')->nullable();
            $table->date('invoice_date')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
