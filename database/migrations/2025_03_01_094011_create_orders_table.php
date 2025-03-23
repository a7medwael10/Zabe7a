<?php

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 20)->unique();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->foreignId('delivery_company_id')->constrained()->onDelete('restrict');
            $table->foreignId('address_id')->constrained('addresses')->onDelete('restrict');
            $table->integer('subtotal');
            $table->integer('discount');
            $table->integer('shipping_cost');
            $table->integer('total');
            $table->tinyInteger('status')->default(OrderStatusEnum::PENDING->value);
            $table->foreignId('payment_method_id')->constrained('payment_methods')->onDelete('restrict');
            $table->tinyInteger('payment_status')->default(PaymentStatusEnum::PENDING->value);
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('slaughtered_at')->nullable();
            $table->timestamp('packed_at')->nullable();
            $table->timestamp('waiting_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('on_way_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('customer_notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['user_id', 'status', 'payment_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
