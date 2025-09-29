<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('discount_audits', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id');
            $t->foreignId('discount_id');
            $t->string('action');
            $t->json('context')->nullable();
            $t->decimal('amount_before', 10, 2)->nullable();
            $t->decimal('amount_after', 10, 2)->nullable();
            $t->decimal('amount_discounted', 10, 2)->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_audits');
    }
};
