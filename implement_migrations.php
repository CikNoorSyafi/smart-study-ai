<?php

/**
 * Script to implement all remaining migrations for Smart Study
 * This script will update the migration files with proper schema definitions
 */

$migrations = [
    // Lessons table
    '2025_06_05_045137_create_lessons_table.php' => [
        'table' => 'lessons',
        'schema' => "
            \$table->uuid('lesson_id')->primary();
            \$table->uuid('course_id');
            \$table->string('title');
            \$table->text('description')->nullable();
            \$table->integer('order_in_course');
            \$table->uuid('created_by')->nullable();
            \$table->timestamp('created_at')->useCurrent();
            \$table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            \$table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');
            \$table->foreign('created_by')->references('user_id')->on('users')->onDelete('set null');
            
            // Indexes
            \$table->index('course_id');
            \$table->index('order_in_course');
            \$table->index('created_by');
        "
    ],
    
    // Learning Content table
    '2025_06_05_045137_create_learning_content_table.php' => [
        'table' => 'learning_content',
        'schema' => "
            \$table->uuid('content_id')->primary();
            \$table->uuid('lesson_id');
            \$table->string('title');
            \$table->string('content_type', 50); // 'text', 'video', 'quiz', 'external_link', 'document'
            \$table->string('content_url', 500)->nullable();
            \$table->text('content_text')->nullable();
            \$table->integer('order_in_lesson');
            \$table->uuid('created_by')->nullable();
            \$table->timestamp('created_at')->useCurrent();
            \$table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            \$table->foreign('lesson_id')->references('lesson_id')->on('lessons')->onDelete('cascade');
            \$table->foreign('created_by')->references('user_id')->on('users')->onDelete('set null');
            
            // Indexes
            \$table->index('lesson_id');
            \$table->index('content_type');
            \$table->index('order_in_lesson');
        "
    ],
    
    // Quizzes table
    '2025_06_05_045138_create_quizzes_table.php' => [
        'table' => 'quizzes',
        'schema' => "
            \$table->uuid('quiz_id')->primary();
            \$table->uuid('content_id')->unique();
            \$table->string('title');
            \$table->text('description')->nullable();
            \$table->integer('duration_minutes')->nullable();
            \$table->decimal('pass_percentage', 5, 2)->nullable();
            \$table->uuid('created_by')->nullable();
            \$table->timestamp('created_at')->useCurrent();
            \$table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            \$table->foreign('content_id')->references('content_id')->on('learning_content')->onDelete('cascade');
            \$table->foreign('created_by')->references('user_id')->on('users')->onDelete('set null');
            
            // Indexes
            \$table->index('content_id');
            \$table->index('created_by');
        "
    ],
    
    // Classes table
    '2025_06_05_045140_create_classes_table.php' => [
        'table' => 'classes',
        'schema' => "
            \$table->uuid('class_id')->primary();
            \$table->uuid('teacher_user_id');
            \$table->string('name');
            \$table->text('description')->nullable();
            \$table->string('enrollment_code', 50)->unique()->nullable();
            \$table->timestamp('created_at')->useCurrent();
            \$table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            \$table->foreign('teacher_user_id')->references('user_id')->on('users')->onDelete('cascade');
            
            // Indexes
            \$table->index('teacher_user_id');
            \$table->index('enrollment_code');
        "
    ],
    
    // Note Subjects junction table
    '2025_06_05_045141_create_note_subjects_table.php' => [
        'table' => 'note_subjects',
        'schema' => "
            \$table->uuid('note_id');
            \$table->uuid('subject_id');
            
            // Composite primary key
            \$table->primary(['note_id', 'subject_id']);
            
            // Foreign key constraints
            \$table->foreign('note_id')->references('note_id')->on('notes')->onDelete('cascade');
            \$table->foreign('subject_id')->references('subject_id')->on('subjects')->onDelete('cascade');
            
            // Indexes
            \$table->index('note_id');
            \$table->index('subject_id');
        "
    ],
    
    // Feedback table
    '2025_06_05_045142_create_feedback_table.php' => [
        'table' => 'feedback',
        'schema' => "
            \$table->uuid('feedback_id')->primary();
            \$table->uuid('user_id')->nullable();
            \$table->uuid('question_id')->nullable();
            \$table->uuid('answer_id')->nullable();
            \$table->integer('rating')->nullable(); // 1-5 stars
            \$table->text('comments')->nullable();
            \$table->timestamp('created_at')->useCurrent();
            
            // Foreign key constraints
            \$table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');
            \$table->foreign('question_id')->references('question_id')->on('questions')->onDelete('cascade');
            \$table->foreign('answer_id')->references('answer_id')->on('answers')->onDelete('cascade');
            
            // Indexes
            \$table->index('user_id');
            \$table->index('question_id');
            \$table->index('answer_id');
            \$table->index('rating');
        "
    ],
    
    // Chat History table
    '2025_06_05_045143_create_chat_history_table.php' => [
        'table' => 'chat_history',
        'schema' => "
            \$table->uuid('chat_id')->primary();
            \$table->uuid('conversation_id');
            \$table->uuid('user_id');
            \$table->string('sender_type', 50); // 'user', 'ai', 'teacher', 'admin'
            \$table->text('message_text');
            \$table->timestamp('timestamp')->useCurrent();
            \$table->string('related_entity_type', 50)->nullable();
            \$table->uuid('related_entity_id')->nullable();
            \$table->string('sentiment', 50)->nullable();
            
            // Foreign key constraints
            \$table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            
            // Indexes
            \$table->index('conversation_id');
            \$table->index('user_id');
            \$table->index('sender_type');
            \$table->index('timestamp');
            \$table->index(['related_entity_type', 'related_entity_id']);
        "
    ]
];

// Function to update migration file
function updateMigrationFile($filename, $tableName, $schema) {
    $migrationPath = "database/migrations/$filename";
    
    if (!file_exists($migrationPath)) {
        echo "Migration file not found: $filename\n";
        return false;
    }
    
    $content = file_get_contents($migrationPath);
    
    // Replace the up method
    $newUpMethod = "    public function up(): void
    {
        Schema::create('$tableName', function (Blueprint \$table) {
            $schema
        });
    }";
    
    // Replace the down method
    $newDownMethod = "    public function down(): void
    {
        Schema::dropIfExists('$tableName');
    }";
    
    // Pattern to match the up method
    $upPattern = '/public function up\(\): void\s*\{[^}]*\}/s';
    $downPattern = '/public function down\(\): void\s*\{[^}]*\}/s';
    
    $content = preg_replace($upPattern, $newUpMethod, $content);
    $content = preg_replace($downPattern, $newDownMethod, $content);
    
    file_put_contents($migrationPath, $content);
    echo "Updated: $filename\n";
    return true;
}

// Update all migrations
echo "Updating migration files...\n\n";

foreach ($migrations as $filename => $config) {
    updateMigrationFile($filename, $config['table'], $config['schema']);
}

echo "\nAll migration files updated successfully!\n";
