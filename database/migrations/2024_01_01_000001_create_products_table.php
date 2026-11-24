<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->string('code', 50)->unique();      // kode / barcode
            $t->string('name', 150);
            $t->string('category', 50)->index();
            $t->unsignedBigInteger('cost_price')->default(0);
            $t->unsignedBigInteger('price');
            $t->integer('stock')->default(0);
            $t->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('products'); }
};
