<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('transactions', function (Blueprint $t) {
            $t->id();
            $t->string('invoice', 30)->unique();
            $t->foreignId('shift_id')->nullable()->constrained()->nullOnDelete();
            $t->string('cashier', 60);
            $t->unsignedBigInteger('subtotal');
            $t->unsignedBigInteger('discount')->default(0);
            $t->unsignedBigInteger('tax')->default(0);
            $t->unsignedBigInteger('total');
            $t->string('method', 20);             // Tunai | QRIS | Kartu
            $t->unsignedBigInteger('paid');
            $t->unsignedBigInteger('change_amount')->default(0);
            $t->timestamps();
        });
        Schema::create('transaction_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $t->string('name', 150);
            $t->unsignedBigInteger('price');
            $t->unsignedBigInteger('cost')->default(0);
            $t->unsignedInteger('qty');
        });
    }
    public function down()
    {
        Schema::dropIfExists('transaction_items');
        Schema::dropIfExists('transactions');
    }
};
