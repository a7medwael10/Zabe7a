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
        Schema::create('addresses', function (Blueprint $table) {
          $table->id();
          $table->foreignId('user_id')->constrained()->onDelete('cascade');
          $table->string('label', 50)->nullable();
          $table->string('country', 100);
          $table->string('city', 100);
          $table->string('district', 100);
          $table->string('street', 150);
          $table->string('postal_code', 20);
          $table->text('building_description')->nullable();
          $table->decimal('latitude', 10, 8)->nullable();
          $table->decimal('longitude', 11, 8)->nullable();
          $table->boolean('is_primary')->default(false);
          $table->softDeletes();
          $table->timestamps();

          $table->index(['user_id', 'is_primary']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
