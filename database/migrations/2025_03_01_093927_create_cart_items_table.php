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
        Schema::create('cart_items', function (Blueprint $table) {
          $table->id();
          $table->foreignId('cart_id')->constrained()->onDelete('cascade');
          $table->morphs('itemable');
          $table->decimal('unit_price', 10, 2)->unsigned();
          $table->integer('quantity')->unsigned()->default(1);
          $table->json('packaging_options')->nullable();
          $table->text('special_instructions')->nullable();
          $table->decimal('subtotal', 10, 2)->unsigned()->default(0.00);
          $table->timestamps();

          $table->index(['itemable_id','cart_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
