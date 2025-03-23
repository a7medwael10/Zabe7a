<?php

use App\Enums\AdStatusEnum;
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
        Schema::create('ads', function (Blueprint $table) {
          $table->id();
          $table->foreignId('category_id')->constrained()->onDelete('restrict');
          $table->string('title', 150);
          $table->string('sub_title', 150)->nullable();
          $table->string('slug', 170)->unique();
          $table->string('thumbnail_path')->nullable();
          $table->text('description');
          $table->decimal('price', 10, 2)->unsigned();
          $table->integer('quantity_available')->unsigned()->default(1);
          $table->integer('quantity_sold')->unsigned()->default(0);
          $table->decimal('weight', 8, 2)->unsigned()->nullable();
          $table->decimal('rating', 3, 2)->unsigned()->default(0.00);
          $table->unsignedInteger('views_count')->default(0);
          $table->unsignedInteger('reviews_count')->default(0);
          $table->tinyInteger('status')->default(AdStatusEnum::PENDING);
          $table->timestamp('approved_at')->nullable();
          $table->timestamp('expires_at')->nullable();
          $table->softDeletes();
          $table->timestamps();

          $table->index([ 'status', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
