#!/bin/bash

# Script to create all remaining migrations for the Smart Study schema

echo "Creating remaining migrations..."

# Curriculum and Course related tables
php artisan make:migration create_curriculum_table
php artisan make:migration create_courses_table
php artisan make:migration create_lessons_table
php artisan make:migration create_learning_content_table

# Quiz system tables
php artisan make:migration create_quizzes_table
php artisan make:migration create_quiz_questions_table
php artisan make:migration create_quiz_question_options_table
php artisan make:migration create_student_quiz_attempts_table
php artisan make:migration create_student_quiz_answers_table

# Class management tables
php artisan make:migration create_classes_table
php artisan make:migration create_class_enrollments_table

# Parent-student associations
php artisan make:migration create_parent_student_associations_table

# Notes and Q&A system (original functionality)
php artisan make:migration create_notes_table
php artisan make:migration create_note_subjects_table
php artisan make:migration create_questions_table
php artisan make:migration create_answers_table

# Feedback and chat system
php artisan make:migration create_feedback_table
php artisan make:migration create_chat_history_table

echo "All migrations created successfully!"
