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
        Schema::create('mutation_forms', function (Blueprint $table) {
            $table->id();
            $table->string('form_number', 50)->unique();
            $table->foreignId('from_department_id')->constrained('departments');
            $table->foreignId('to_department_id')->constrained('departments');
            $table->text('reason');
            $table->enum('status', ['draft', 'waiting_receiver', 'ready_for_execution', 'archived', 'rejected'])->default('draft');
            $table->foreignId('sender_user_id')->constrained('users');
            $table->foreignId('receiver_user_id')->nullable()->constrained('users');
            $table->foreignId('executed_by_user_id')->nullable()->constrained('users');
            $table->text('sender_signature')->nullable();
            $table->text('receiver_signature')->nullable();
            $table->timestamp('sender_signed_at')->nullable();
            $table->timestamp('receiver_signed_at')->nullable();
            $table->string('archived_pdf_path', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutation_forms');
    }
};
