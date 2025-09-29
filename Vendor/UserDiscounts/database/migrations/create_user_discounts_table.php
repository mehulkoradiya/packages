<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('user_discounts', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('discount_id')->constrained('discounts')->cascadeOnDelete();
            $t->integer('usage_count')->default(0);
            $t->integer('usage_limit')->nullable();
            $t->timestamp('assigned_at')->nullable();
            $t->timestamp('revoked_at')->nullable();
            $t->timestamps();

            $t->unique(['user_id','discount_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_discounts');
    }
};
