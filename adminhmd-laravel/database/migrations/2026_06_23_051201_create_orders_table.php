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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number',255);
            $table->foreignID('user_id')->constrained('users')->cascadeonDelete();
            $table->decimal('total_amount',10,2);
            $table->enum('payment_status',['pending','paid','failed'])->default('pending');
            $table->enum('order_status',['pending','processing','shipped','completed','cancelled'])->default('pending');
            $table->text('shipping_address')->nullable();
            $table->text('billing_address')->nullable();
            $table->enum('payment_method',['cash_on_delivery','paypal','striped'])->nullable();
            $table->string('transaction_id',255)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
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
