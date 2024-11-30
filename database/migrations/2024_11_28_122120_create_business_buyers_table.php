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
        Schema::create('business_buyers', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID for the Business_Buyers table
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade'); // Foreign key referencing businesses table
            $table->string('company_name'); // Company name as a string
            $table->text('location'); // Location as text
            $table->text('contact_details')->nullable(); // Contact details as nullable text field
            $table->timestamps(); // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_buyers');
    }
};
