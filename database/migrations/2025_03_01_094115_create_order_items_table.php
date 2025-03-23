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
          $table->morphs('itemable');
          $table->string('title', 150);
          $table->decimal('unit_price', 10, 2)->unsigned();
          $table->integer('quantity')->unsigned();
          $table->json('packaging_options');
          $table->text('notes')->nullable();
          $table->decimal('total', 10, 2)->unsigned()->default(0.00);
          $table->timestamps();

          $table->index(['itemable_id','order_id']);
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
