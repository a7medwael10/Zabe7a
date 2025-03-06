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
        Schema::create('sliders', function (Blueprint $table) {
          $table->id();
          $table->string('image_path');
          $table->string('title', 100)->nullable();
          $table->text('description')->nullable();
          $table->enum('target_type', ['category', 'ad', 'external_url', 'none'])->nullable();
          $table->unsignedBigInteger('target_id')->nullable();
          $table->string('target_url')->nullable();
          $table->integer('sort_order')->unsigned()->default(0);
          $table->boolean('is_active')->default(true);
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
