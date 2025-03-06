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
        Schema::create('coupons', function (Blueprint $table) {
          $table->id();
          $table->string('code', 20)->unique();
          $table->enum('type', ['percentage', 'fixed'])->index();
          $table->decimal('value', 10, 2)->unsigned();
          $table->decimal('minimum_order_amount', 10, 2)->unsigned()->nullable();
          $table->integer('max_usage_per_user')->unsigned()->default(1);
          $table->integer('total_usage_limit')->unsigned()->nullable();
          $table->integer('used_count')->unsigned()->default(0);
          $table->dateTime('valid_from');
          $table->dateTime('valid_to');
          $table->boolean('is_active')->default(true);
          $table->timestamps();

          $table->index(['valid_from', 'valid_to', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
