<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('nms_classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('section')->nullable();
            $table->unsignedInteger('capacity')->default(30);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('nms_parents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        Schema::create('nms_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->nullable()->constrained('nms_classrooms')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('nms_parents')->nullOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('dob')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('roll_no')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        Schema::create('nms_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('nms_students')->cascadeOnDelete();
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('present');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['student_id', 'date']);
        });

        Schema::create('nms_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('nms_students')->cascadeOnDelete();
            $table->string('number')->unique();
            $table->date('issue_date');
            $table->date('due_date');
            $table->string('currency', 3)->default('USD');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->string('status')->default('unpaid');
            $table->timestamps();
        });

        Schema::create('nms_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('nms_invoices')->cascadeOnDelete();
            $table->string('description');
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('line_total', 10, 2);
            $table->timestamps();
        });

        Schema::create('nms_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('nms_invoices')->cascadeOnDelete();
            $table->string('provider')->default('manual');
            $table->string('provider_ref')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('nms_comm_batches', function (Blueprint $table) {
            $table->id();
            $table->string('channel'); // email, sms, whatsapp
            $table->string('subject')->nullable();
            $table->text('content');
            $table->unsignedInteger('total')->default(0);
            $table->unsignedInteger('sent')->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('nms_comm_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('nms_comm_batches')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('nms_parents')->nullOnDelete();
            $table->foreignId('student_id')->nullable()->constrained('nms_students')->nullOnDelete();
            $table->string('status')->default('queued');
            $table->text('error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nms_comm_recipients');
        Schema::dropIfExists('nms_comm_batches');
        Schema::dropIfExists('nms_payments');
        Schema::dropIfExists('nms_invoice_items');
        Schema::dropIfExists('nms_invoices');
        Schema::dropIfExists('nms_attendance');
        Schema::dropIfExists('nms_students');
        Schema::dropIfExists('nms_parents');
        Schema::dropIfExists('nms_classrooms');
    }
};
