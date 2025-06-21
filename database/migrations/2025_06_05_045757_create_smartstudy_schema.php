<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Enhance existing users table (check if columns exist first)
        if (!Schema::hasColumn('users', 'user_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->uuid('user_id')->nullable()->after('id');
                $table->string('username')->nullable()->after('user_id');
                $table->enum('role', ['admin', 'teacher', 'student', 'parent'])->default('student')->after('password');
                $table->boolean('is_active')->default(true)->after('role');
                $table->timestamp('last_login_at')->nullable()->after('updated_at');
            });
        }

        // Update existing records with UUIDs and usernames if they're empty
        $users = DB::table('users')->whereNull('user_id')->orWhere('user_id', '')->get();
        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'user_id' => \Illuminate\Support\Str::uuid()->toString(),
                    'username' => 'user_' . $user->id . '_' . time()
                ]);
        }

        // Make user_id and username non-nullable and unique if not already
        if (Schema::hasColumn('users', 'user_id')) {
            // For SQLite, we need to check if the unique constraint already exists differently
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->uuid('user_id')->nullable(false)->unique()->change();
                    $table->string('username')->nullable(false)->unique()->change();
                });
            } catch (\Exception $e) {
                // Constraint might already exist, continue
            }
        }

        // 2. Create Subjects table
        Schema::create('subjects', function (Blueprint $table) {
            $table->uuid('subject_id')->primary();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->index('name');
        });

        // 3. Create Notes table (core functionality)
        Schema::create('notes', function (Blueprint $table) {
            $table->uuid('note_id')->primary();
            $table->uuid('user_id');
            $table->string('title');
            $table->text('content');
            $table->timestamps();
            $table->string('status', 50)->default('draft');

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

            $table->index('user_id');
            $table->index('status');
            $table->index('created_at');
        });

        // 4. Create Questions table
        Schema::create('questions', function (Blueprint $table) {
            $table->uuid('question_id')->primary();
            $table->uuid('note_id');
            $table->uuid('user_id');
            $table->text('question_text');
            $table->string('generated_by', 50)->default('Manual');
            $table->string('difficulty', 50)->nullable();
            $table->timestamps();

            $table->foreign('note_id')->references('note_id')->on('notes')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

            $table->index('note_id');
            $table->index('user_id');
            $table->index('generated_by');
            $table->index('difficulty');
        });

        // 5. Create Answers table
        Schema::create('answers', function (Blueprint $table) {
            $table->uuid('answer_id')->primary();
            $table->uuid('question_id');
            $table->text('answer_text');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();

            $table->foreign('question_id')->references('question_id')->on('questions')->onDelete('cascade');

            $table->index('question_id');
            $table->index('is_correct');
        });

        // 6. Create Note-Subjects junction table
        Schema::create('note_subjects', function (Blueprint $table) {
            $table->uuid('note_id');
            $table->uuid('subject_id');

            $table->primary(['note_id', 'subject_id']);

            $table->foreign('note_id')->references('note_id')->on('notes')->onDelete('cascade');
            $table->foreign('subject_id')->references('subject_id')->on('subjects')->onDelete('cascade');

            $table->index('note_id');
            $table->index('subject_id');
        });

        // 7. Create User Profiles table
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->uuid('profile_id')->primary();
            $table->uuid('user_id')->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('phone_number', 50)->unique()->nullable();
            $table->string('profile_picture_url')->nullable();
            $table->text('bio')->nullable();
            $table->string('preferred_language', 10)->default('en');
            $table->string('timezone', 50)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->index('user_id');
        });

        // 8. Create User-Subjects junction table
        Schema::create('user_subjects', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->uuid('subject_id');
            $table->string('role_in_subject', 50)->nullable(); // 'student', 'teacher', 'enthusiast'
            $table->string('level', 50)->nullable(); // 'beginner', 'intermediate', 'advanced'

            $table->primary(['user_id', 'subject_id']);

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('subject_id')->references('subject_id')->on('subjects')->onDelete('cascade');

            $table->index('user_id');
            $table->index('subject_id');
            $table->index('role_in_subject');
        });

        // 9. Create Feedback table
        Schema::create('feedback', function (Blueprint $table) {
            $table->uuid('feedback_id')->primary();
            $table->uuid('user_id')->nullable();
            $table->uuid('question_id')->nullable();
            $table->uuid('answer_id')->nullable();
            $table->integer('rating')->nullable();
            $table->text('comments')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');
            $table->foreign('question_id')->references('question_id')->on('questions')->onDelete('cascade');
            $table->foreign('answer_id')->references('answer_id')->on('answers')->onDelete('cascade');

            $table->index('user_id');
            $table->index('question_id');
            $table->index('answer_id');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
        Schema::dropIfExists('user_subjects');
        Schema::dropIfExists('user_profiles');
        Schema::dropIfExists('note_subjects');
        Schema::dropIfExists('answers');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('notes');
        Schema::dropIfExists('subjects');

        // Remove added columns from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'username', 'role', 'is_active', 'last_login_at']);
        });
    }
};
