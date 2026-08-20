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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code', 50)->unique();
            $table->string('name', 150);
            $table->decimal('purchase_price', 15, 2);
            $table->date('purchase_date');
            $table->foreignId('current_department_id')->constrained('departments');
            $table->enum('status', ['in_storage', 'active', 'under_repair', 'disposed'])->default('in_storage');
            $table->foreignId('created_by_user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
