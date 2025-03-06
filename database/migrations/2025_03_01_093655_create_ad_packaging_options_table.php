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
        Schema::create('ad_packaging_options', function (Blueprint $table) {
          $table->id();
          $table->foreignId('ad_id')->constrained()->onDelete('cascade');
          $table->string('type', 50);
          $table->decimal('additional_price', 10, 2)->unsigned()->default(0.00);
          $table->boolean('is_available')->default(true);
          $table->integer('sort_order')->unsigned()->default(0);
          $table->timestamps();

          $table->index('ad_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_packaging_options');
    }
};
