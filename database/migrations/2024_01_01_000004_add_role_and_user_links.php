<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $t) {
            $t->string('role', 20)->default('kasir')->after('password');   // admin | kasir
            $t->boolean('is_active')->default(true)->after('role');
        });
        Schema::table('shifts', function (Blueprint $t) {
            $t->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });
        Schema::table('transactions', function (Blueprint $t) {
            $t->foreignId('user_id')->nullable()->after('shift_id')->constrained()->nullOnDelete();
        });
    }
    public function down()
    {
        Schema::table('transactions', fn (Blueprint $t) => $t->dropConstrainedForeignId('user_id'));
        Schema::table('shifts', fn (Blueprint $t) => $t->dropConstrainedForeignId('user_id'));
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn(['role', 'is_active']));
    }
};
