<?php

/**
 * Smart Study Comprehensive Functionality Demo
 *
 * This script demonstrates all the implemented features of the Smart Study schema
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\User;
use App\Models\Subject;
use App\Models\Note;
use App\Models\Question;
use App\Models\Answer;
use App\Models\UserProfile;
use App\Models\Feedback;

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

class SmartStudyDemo
{
    public function runDemo()
    {
        echo "=== Smart Study Comprehensive Functionality Demo ===\n\n";
        
        $this->demonstrateUserManagement();
        $this->demonstrateSubjectManagement();
        $this->demonstrateNoteManagement();
        $this->demonstrateQuestionAnswerSystem();
        $this->demonstrateUserProfiles();
        $this->demonstrateFeedbackSystem();
        $this->demonstrateRelationships();
        $this->demonstrateAdvancedQueries();
        
        echo "\n=== Demo Complete! ===\n";
    }

    private function demonstrateUserManagement()
    {
        echo "🔐 USER MANAGEMENT DEMO\n";
        echo "=" . str_repeat("=", 50) . "\n";
        
        // Show user statistics
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $usersByRole = User::select('role', \DB::raw('count(*) as count'))
                          ->groupBy('role')
                          ->get();
        
        echo "Total Users: {$totalUsers}\n";
        echo "Active Users: {$activeUsers}\n";
        echo "Users by Role:\n";
        foreach ($usersByRole as $roleData) {
            echo "  - {$roleData->role}: {$roleData->count}\n";
        }
        
        // Demonstrate user methods
        $admin = User::where('role', 'admin')->first();
        $student = User::where('role', 'student')->first();
        $teacher = User::where('role', 'teacher')->first();
        
        echo "\nUser Role Methods Demo:\n";
        echo "Admin '{$admin->name}' isAdmin(): " . ($admin->isAdmin() ? 'true' : 'false') . "\n";
        echo "Student '{$student->name}' isStudent(): " . ($student->isStudent() ? 'true' : 'false') . "\n";
        echo "Teacher '{$teacher->name}' isTeacher(): " . ($teacher->isTeacher() ? 'true' : 'false') . "\n";
        
        echo "\n";
    }

    private function demonstrateSubjectManagement()
    {
        echo "📚 SUBJECT MANAGEMENT DEMO\n";
        echo "=" . str_repeat("=", 50) . "\n";
        
        $subjects = Subject::all();
        echo "Available Subjects ({$subjects->count()}):\n";
        foreach ($subjects->take(5) as $subject) {
            echo "  - {$subject->name}: {$subject->description}\n";
        }
        
        // Demonstrate subject relationships
        $mathSubject = Subject::where('name', 'Mathematics')->first();
        if ($mathSubject) {
            $mathUsers = $mathSubject->users()->count();
            $mathNotes = $mathSubject->notes()->count();
            echo "\nMathematics Subject Stats:\n";
            echo "  - Associated Users: {$mathUsers}\n";
            echo "  - Associated Notes: {$mathNotes}\n";
        }
        
        echo "\n";
    }

    private function demonstrateNoteManagement()
    {
        echo "📝 NOTE MANAGEMENT DEMO\n";
        echo "=" . str_repeat("=", 50) . "\n";
        
        $totalNotes = Note::count();
        $publishedNotes = Note::published()->count();
        $draftNotes = Note::draft()->count();
        
        echo "Note Statistics:\n";
        echo "  - Total Notes: {$totalNotes}\n";
        echo "  - Published: {$publishedNotes}\n";
        echo "  - Drafts: {$draftNotes}\n";
        
        // Show sample notes
        echo "\nSample Published Notes:\n";
        $sampleNotes = Note::published()->with('user', 'subjects')->take(3)->get();
        foreach ($sampleNotes as $note) {
            echo "  - '{$note->title}' by {$note->user->name}\n";
            echo "    Word Count: {$note->word_count} | Excerpt: " . substr($note->excerpt, 0, 50) . "...\n";
            if ($note->subjects->count() > 0) {
                echo "    Subjects: " . $note->subjects->pluck('name')->join(', ') . "\n";
            }
        }
        
        echo "\n";
    }

    private function demonstrateQuestionAnswerSystem()
    {
        echo "❓ QUESTION & ANSWER SYSTEM DEMO\n";
        echo "=" . str_repeat("=", 50) . "\n";
        
        $totalQuestions = Question::count();
        $aiGenerated = Question::aiGenerated()->count();
        $manualQuestions = Question::manual()->count();
        $totalAnswers = Answer::count();
        $correctAnswers = Answer::correct()->count();
        
        echo "Q&A Statistics:\n";
        echo "  - Total Questions: {$totalQuestions}\n";
        echo "  - AI Generated: {$aiGenerated}\n";
        echo "  - Manual Questions: {$manualQuestions}\n";
        echo "  - Total Answers: {$totalAnswers}\n";
        echo "  - Correct Answers: {$correctAnswers}\n";
        
        // Show sample Q&A
        echo "\nSample Questions with Answers:\n";
        $sampleQuestions = Question::with('answers', 'note')->take(3)->get();
        foreach ($sampleQuestions as $question) {
            echo "  Q: {$question->question_text}\n";
            echo "     Difficulty: {$question->difficulty} | Generated by: {$question->generated_by}\n";
            echo "     From Note: '{$question->note->title}'\n";
            
            $correctAnswer = $question->correctAnswer();
            if ($correctAnswer) {
                echo "     Correct Answer: {$correctAnswer->answer_text}\n";
            }
            echo "\n";
        }
        
        echo "\n";
    }

    private function demonstrateUserProfiles()
    {
        echo "👤 USER PROFILES DEMO\n";
        echo "=" . str_repeat("=", 50) . "\n";
        
        $profileCount = UserProfile::count();
        echo "Total User Profiles: {$profileCount}\n";
        
        // Create a sample profile
        $user = User::doesntHave('profile')->first();
        if ($user) {
            $profile = UserProfile::create([
                'user_id' => $user->user_id,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'date_of_birth' => '1990-05-15',
                'phone_number' => '+1234567890',
                'bio' => 'Passionate learner and educator.',
                'preferred_language' => 'en',
                'timezone' => 'UTC',
            ]);
            
            echo "\nCreated Sample Profile:\n";
            echo "  - Full Name: {$profile->full_name}\n";
            echo "  - Initials: {$profile->initials}\n";
            echo "  - Age: {$profile->age}\n";
            echo "  - Profile Complete: " . ($profile->isComplete() ? 'Yes' : 'No') . "\n";
            echo "  - Completion Percentage: {$profile->getCompletionPercentage()}%\n";
        }
        
        echo "\n";
    }

    private function demonstrateFeedbackSystem()
    {
        echo "💬 FEEDBACK SYSTEM DEMO\n";
        echo "=" . str_repeat("=", 50) . "\n";
        
        $totalFeedback = Feedback::count();
        $positiveFeedback = Feedback::positive()->count();
        $negativeFeedback = Feedback::negative()->count();
        
        echo "Feedback Statistics:\n";
        echo "  - Total Feedback: {$totalFeedback}\n";
        echo "  - Positive (4-5 stars): {$positiveFeedback}\n";
        echo "  - Negative (1-2 stars): {$negativeFeedback}\n";
        
        if ($totalFeedback > 0) {
            $avgRating = Feedback::avg('rating');
            echo "  - Average Rating: " . round($avgRating, 2) . "/5\n";
            
            echo "\nSample Feedback:\n";
            $sampleFeedback = Feedback::with('user', 'question')->take(3)->get();
            foreach ($sampleFeedback as $feedback) {
                echo "  - Rating: {$feedback->rating}/5 ({$feedback->type})\n";
                echo "    By: {$feedback->user->name}\n";
                echo "    Comment: {$feedback->comments}\n";
                echo "\n";
            }
        }
        
        echo "\n";
    }

    private function demonstrateRelationships()
    {
        echo "🔗 RELATIONSHIPS DEMO\n";
        echo "=" . str_repeat("=", 50) . "\n";
        
        // User-Subject relationships
        $userSubjectCount = \DB::table('user_subjects')->count();
        echo "User-Subject Associations: {$userSubjectCount}\n";
        
        // Note-Subject relationships
        $noteSubjectCount = \DB::table('note_subjects')->count();
        echo "Note-Subject Associations: {$noteSubjectCount}\n";
        
        // Demonstrate complex relationships
        $user = User::with('subjects', 'notes.questions.answers')->first();
        echo "\nComplex Relationship Example - User: {$user->name}\n";
        echo "  - Associated Subjects: {$user->subjects->count()}\n";
        echo "  - Created Notes: {$user->notes->count()}\n";
        
        $totalQuestions = $user->notes->sum(function($note) {
            return $note->questions->count();
        });
        echo "  - Total Questions from Notes: {$totalQuestions}\n";
        
        echo "\n";
    }

    private function demonstrateAdvancedQueries()
    {
        echo "🔍 ADVANCED QUERIES DEMO\n";
        echo "=" . str_repeat("=", 50) . "\n";
        
        // Most active users (by note count)
        echo "Most Active Users (by notes created):\n";
        $activeUsers = User::withCount('notes')
                          ->orderBy('notes_count', 'desc')
                          ->take(3)
                          ->get();
        
        foreach ($activeUsers as $user) {
            echo "  - {$user->name}: {$user->notes_count} notes\n";
        }
        
        // Most popular subjects (by note count)
        echo "\nMost Popular Subjects (by associated notes):\n";
        $popularSubjects = Subject::withCount('notes')
                                 ->orderBy('notes_count', 'desc')
                                 ->take(3)
                                 ->get();
        
        foreach ($popularSubjects as $subject) {
            echo "  - {$subject->name}: {$subject->notes_count} notes\n";
        }
        
        // Questions by difficulty
        echo "\nQuestions by Difficulty:\n";
        $difficultyStats = Question::select('difficulty', \DB::raw('count(*) as count'))
                                  ->whereNotNull('difficulty')
                                  ->groupBy('difficulty')
                                  ->get();
        
        foreach ($difficultyStats as $stat) {
            echo "  - {$stat->difficulty}: {$stat->count} questions\n";
        }
        
        // AI vs Manual questions
        echo "\nQuestion Generation Methods:\n";
        $generationStats = Question::select('generated_by', \DB::raw('count(*) as count'))
                                  ->groupBy('generated_by')
                                  ->get();
        
        foreach ($generationStats as $stat) {
            echo "  - {$stat->generated_by}: {$stat->count} questions\n";
        }
        
        echo "\n";
    }
}

// Run the comprehensive demo
$demo = new SmartStudyDemo();
$demo->runDemo();

echo "=== Authentication Test Credentials ===\n";
echo "Use these credentials to test the authentication system:\n";
echo "Admin: admin@smartstudy.com / password123\n";
echo "Demo: demo@smartstudy.com / demo123\n";
echo "Test: test@smartstudy.com / test123\n";
echo "\n=== Test URLs ===\n";
echo "Login: http://127.0.0.1:8000/login\n";
echo "Register: http://127.0.0.1:8000/register\n";
echo "Dashboard: http://127.0.0.1:8000/dashboard\n";
echo "Admin: http://127.0.0.1:8000/admin\n";
