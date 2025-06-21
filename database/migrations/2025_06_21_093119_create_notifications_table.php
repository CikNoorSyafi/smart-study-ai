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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('notification_id')->primary();
            $table->uuid('user_id');
            $table->string('type'); // 'student_enrollment', 'content_update', 'question_response', 'performance_alert', 'message', 'system_update'
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional data like student_id, note_id, etc.
            $table->boolean('is_read')->default(false);
            $table->string('priority')->default('normal'); // 'low', 'normal', 'high', 'urgent'
            $table->string('icon')->nullable(); // FontAwesome icon class
            $table->string('color')->default('blue'); // Notification color theme
            $table->string('action_url')->nullable(); // URL to navigate when clicked
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'is_read']);
            $table->index(['user_id', 'created_at']);
            $table->index('type');

            // Foreign key
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
