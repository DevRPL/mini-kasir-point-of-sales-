<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('shifts', function (Blueprint $t) {
            $t->id();
            $t->string('cashier', 60);
            $t->unsignedBigInteger('opening_cash')->default(0);
            $t->timestamp('started_at');
            $t->timestamp('closed_at')->nullable();
            $t->unsignedBigInteger('cash_total')->default(0);
            $t->unsignedBigInteger('noncash_total')->default(0);
            $t->unsignedInteger('trx_count')->default(0);
            $t->bigInteger('expected_cash')->nullable();
            $t->bigInteger('closing_cash')->nullable();
            $t->bigInteger('difference')->nullable();
            $t->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('shifts'); }
};
