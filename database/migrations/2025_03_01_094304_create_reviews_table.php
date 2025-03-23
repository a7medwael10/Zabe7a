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
        Schema::create('reviews', function (Blueprint $table) {
          $table->id();
          $table->foreignId('user_id')->constrained()->onDelete('cascade');
          $table->morphs('reviewable');
          $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
          $table->unsignedTinyInteger('rating');
          $table->text('comment')->nullable();
          $table->softDeletes();
          $table->timestamps();
          $table->unique(['user_id','reviewable_id', 'order_id'], 'unique_user_ad_order_review');
          $table->index([ 'reviewable_id']);
          $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
