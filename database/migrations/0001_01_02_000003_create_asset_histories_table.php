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
        Schema::create('asset_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets');
            $table->enum('action_type', ['registration', 'initial_dispatch', 'department_mutation', 'repair', 'disposal']);
            $table->foreignId('from_department_id')->nullable()->constrained('departments');
            $table->foreignId('to_department_id')->constrained('departments');
            $table->foreignId('actor_user_id')->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_histories');
    }
};
