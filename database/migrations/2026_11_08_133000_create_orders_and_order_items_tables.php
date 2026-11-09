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
        // 1. Kolom tambahan pada tabel users jika belum ada
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('email');
            }
            if (! Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
        });

        // 2. Tabel orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('total_price')->default(0);
            $table->string('payment_method')->default('transfer_bank');
            $table->string('shipping_courier')->default('JNE');
            $table->unsignedBigInteger('shipping_cost')->default(0);
            $table->string('tracking_number')->nullable();
            $table->enum('status', ['pending', 'shipped', 'completed'])->default('pending');
            $table->string('customer_name')->nullable();
            $table->string('customer_phone', 25)->nullable();
            $table->text('customer_address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Tabel order_items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedBigInteger('price')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
