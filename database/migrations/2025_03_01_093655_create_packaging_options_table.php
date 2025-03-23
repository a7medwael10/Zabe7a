<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('packaging_options', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // cutting, packaging, liver, head, etc.
            $table->string('name');
            $table->decimal('extra_price', 10, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packaging_options');
    }
};
