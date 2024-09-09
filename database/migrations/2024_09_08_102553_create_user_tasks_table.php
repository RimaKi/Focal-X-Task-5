<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->text('description');
            $table->dateTime('due_date')->nullable();
            $table->enum('priority', ['low', 'medium', 'important', 'very_important']);
            $table->enum('status', ['pending', 'processing', 'done', 'failed'])->default('pending');
            $table->string('assigned_to')->nullable();
            $table->string('created_by');

            $table->foreign('assigned_to')->references('uuid')->on('users');
            $table->foreign('created_by')->references('uuid')->on('users');

            // Use custom names for timestamps
            $table->timestamp('create')->nullable(); // Replaces created_at
            $table->timestamp('update')->nullable(); // Replaces updated_at

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_tasks');
    }
};
