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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title', 150);
            $table->string('sub_title', 150)->nullable();
            $table->string('slug', 170)->unique();
            $table->string('thumbnail_path')->nullable();
            $table->text('description')->nullable();
            $table->decimal('original_price', 10, 2);
            $table->decimal('discount_percentage', 5, 2)->default(0.00);
            $table->decimal('offer_price', 10, 2)->nullable();
            $table->string('gift')->nullable();
            $table->decimal('rating', 3, 2)->unsigned()->default(0.00);
            $table->integer('quantity_sold')->unsigned()->default(0);
            $table->integer('quantity_available')->unsigned()->default(1);
            $table->decimal('weight', 8, 2)->unsigned()->nullable();
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('reviews_count')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'starts_at', 'expires_at']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
