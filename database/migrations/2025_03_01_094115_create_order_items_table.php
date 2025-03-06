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
        Schema::create('order_items', function (Blueprint $table) {
          $table->id();
          $table->foreignId('order_id')->constrained()->onDelete('cascade');
          $table->foreignId('ad_id')->constrained()->onDelete('restrict');
          $table->string('title', 150);
          $table->decimal('unit_price', 10, 2)->unsigned();
          $table->integer('quantity')->unsigned();
          $table->json('packaging_options')->nullable();
          $table->decimal('subtotal', 10, 2)->unsigned();
          $table->enum('status', ['pending', 'shipped', 'delivered', 'returned'])->default('pending');
          $table->timestamps();

          $table->index(['order_id', 'ad_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
