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
        Schema::create('delivery_companies', function (Blueprint $table) {
          $table->id();
          $table->string('name', 100);
          $table->string('logo')->nullable();
          $table->text('description')->nullable();
          $table->decimal('base_price', 10, 2)->unsigned();
          $table->decimal('price_per_km', 10, 2)->unsigned()->default(0.00);
          $table->integer('estimated_delivery_days')->unsigned();
          $table->boolean('is_active')->default(true);
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_companies');
    }
};
