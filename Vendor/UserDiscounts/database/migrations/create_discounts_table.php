<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('discounts', function (Blueprint $t) {
            $t->id();
            $t->string('code')->nullable()->unique();
            $t->enum('type', ['percentage', 'fixed']);
            $t->decimal('value', 8, 2);
            $t->boolean('active')->default(true);
            $t->timestamp('starts_at')->nullable();
            $t->timestamp('ends_at')->nullable();
            $t->integer('per_user_limit')->nullable();
            $t->integer('max_uses')->nullable();
            $t->integer('usage_count')->default(0);
            $t->integer('stacking_priority')->default(0);
            $t->boolean('stackable')->default(true);
            $t->json('metadata')->nullable();
            $t->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('discounts');
    }
};
