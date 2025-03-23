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
        Schema::create('categories', function (Blueprint $table) {
          $table->id();
          $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
          $table->string('name', 100);
          $table->string('slug', 120)->unique();
          $table->string('logo')->nullable();
          $table->text('description')->nullable();
          $table->integer('sort_order')->unsigned()->default(0);
          $table->boolean('is_active')->default(true);
          $table->softDeletes();
          $table->timestamps();

          $table->index(['section_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
