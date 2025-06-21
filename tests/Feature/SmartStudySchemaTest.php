<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Subject;
use App\Models\Note;
use App\Models\Question;
use App\Models\Answer;
use App\Models\UserProfile;
use App\Models\Feedback;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SmartStudySchemaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test User model with new schema.
     */
    public function test_user_model_with_new_schema(): void
    {
        $user = User::factory()->create([
            'role' => 'student',
            'is_active' => true,
        ]);

        $this->assertNotNull($user->user_id);
        $this->assertNotNull($user->username);
        $this->assertEquals('student', $user->role);
        $this->assertTrue($user->is_active);
        $this->assertTrue($user->isStudent());
        $this->assertFalse($user->isAdmin());
    }

    /**
     * Test Subject creation and relationships.
     */
    public function test_subject_creation_and_relationships(): void
    {
        $subject = Subject::create([
            'name' => 'Test Mathematics',
            'description' => 'Test subject for mathematics',
        ]);

        $this->assertNotNull($subject->subject_id);
        $this->assertEquals('Test Mathematics', $subject->name);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $subject->users);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $subject->notes);
    }

    /**
     * Test Note creation and relationships.
     */
    public function test_note_creation_and_relationships(): void
    {
        $user = User::factory()->create();
        $subject = Subject::create([
            'name' => 'Test Subject',
            'description' => 'Test description',
        ]);

        $note = Note::create([
            'user_id' => $user->user_id,
            'title' => 'Test Note',
            'content' => 'This is test content for the note.',
            'status' => 'published',
        ]);

        // Associate note with subject
        $note->subjects()->attach($subject->subject_id);

        $this->assertNotNull($note->note_id);
        $this->assertEquals($user->user_id, $note->user_id);
        $this->assertEquals('Test Note', $note->title);
        $this->assertEquals('published', $note->status);
        
        // Test relationships
        $this->assertEquals($user->user_id, $note->user->user_id);
        $this->assertTrue($note->subjects->contains($subject));
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $note->questions);
    }

    /**
     * Test Question and Answer creation.
     */
    public function test_question_answer_creation(): void
    {
        $user = User::factory()->create();
        $note = Note::create([
            'user_id' => $user->user_id,
            'title' => 'Test Note',
            'content' => 'Test content',
            'status' => 'published',
        ]);

        $question = Question::create([
            'note_id' => $note->note_id,
            'user_id' => $user->user_id,
            'question_text' => 'What is 2 + 2?',
            'difficulty' => 'easy',
            'generated_by' => 'Manual',
        ]);

        $correctAnswer = Answer::create([
            'question_id' => $question->question_id,
            'answer_text' => '4',
            'is_correct' => true,
        ]);

        $incorrectAnswer = Answer::create([
            'question_id' => $question->question_id,
            'answer_text' => '5',
            'is_correct' => false,
        ]);

        $this->assertNotNull($question->question_id);
        $this->assertEquals('What is 2 + 2?', $question->question_text);
        $this->assertEquals('easy', $question->difficulty);
        $this->assertFalse($question->isAiGenerated());
        
        // Test relationships
        $this->assertEquals($note->note_id, $question->note->note_id);
        $this->assertEquals($user->user_id, $question->user->user_id);
        $this->assertCount(2, $question->answers);
        
        // Test correct answer
        $this->assertEquals('4', $question->correctAnswer()->answer_text);
        $this->assertTrue($correctAnswer->is_correct);
        $this->assertFalse($incorrectAnswer->is_correct);
    }

    /**
     * Test UserProfile creation and relationships.
     */
    public function test_user_profile_creation(): void
    {
        $user = User::factory()->create();
        
        $profile = UserProfile::create([
            'user_id' => $user->user_id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'date_of_birth' => '1990-01-01',
            'phone_number' => '+1234567890',
            'bio' => 'Test bio',
            'preferred_language' => 'en',
            'timezone' => 'UTC',
        ]);

        $this->assertNotNull($profile->profile_id);
        $this->assertEquals($user->user_id, $profile->user_id);
        $this->assertEquals('John Doe', $profile->full_name);
        $this->assertEquals('JD', $profile->initials);
        $this->assertTrue($profile->isComplete());
        $this->assertEquals(100, $profile->getCompletionPercentage());
        
        // Test relationship
        $this->assertEquals($user->user_id, $profile->user->user_id);
        $this->assertEquals($profile->profile_id, $user->profile->profile_id);
    }

    /**
     * Test Feedback creation and relationships.
     */
    public function test_feedback_creation(): void
    {
        $user = User::factory()->create();
        $note = Note::create([
            'user_id' => $user->user_id,
            'title' => 'Test Note',
            'content' => 'Test content',
        ]);
        
        $question = Question::create([
            'note_id' => $note->note_id,
            'user_id' => $user->user_id,
            'question_text' => 'Test question?',
        ]);

        $answer = Answer::create([
            'question_id' => $question->question_id,
            'answer_text' => 'Test answer',
            'is_correct' => true,
        ]);

        $feedback = Feedback::create([
            'user_id' => $user->user_id,
            'question_id' => $question->question_id,
            'answer_id' => $answer->answer_id,
            'rating' => 5,
            'comments' => 'Excellent question!',
        ]);

        $this->assertNotNull($feedback->feedback_id);
        $this->assertEquals(5, $feedback->rating);
        $this->assertTrue($feedback->isPositive());
        $this->assertFalse($feedback->isNegative());
        $this->assertEquals('positive', $feedback->type);
        
        // Test relationships
        $this->assertEquals($user->user_id, $feedback->user->user_id);
        $this->assertEquals($question->question_id, $feedback->question->question_id);
        $this->assertEquals($answer->answer_id, $feedback->answer->answer_id);
    }

    /**
     * Test User-Subject many-to-many relationship.
     */
    public function test_user_subject_relationship(): void
    {
        $user = User::factory()->create(['role' => 'teacher']);
        $subject = Subject::create([
            'name' => 'Mathematics',
            'description' => 'Math subject',
        ]);

        // Attach user to subject with pivot data
        $user->subjects()->attach($subject->subject_id, [
            'role_in_subject' => 'teacher',
            'level' => 'advanced',
        ]);

        $this->assertTrue($user->subjects->contains($subject));
        $this->assertTrue($subject->users->contains($user));
        
        // Test pivot data
        $userSubject = $user->subjects()->where('subject_id', $subject->subject_id)->first();
        $this->assertEquals('teacher', $userSubject->pivot->role_in_subject);
        $this->assertEquals('advanced', $userSubject->pivot->level);
    }

    /**
     * Test Note-Subject many-to-many relationship.
     */
    public function test_note_subject_relationship(): void
    {
        $user = User::factory()->create();
        $subject = Subject::create([
            'name' => 'Science',
            'description' => 'Science subject',
        ]);
        
        $note = Note::create([
            'user_id' => $user->user_id,
            'title' => 'Science Note',
            'content' => 'Science content',
        ]);

        $note->subjects()->attach($subject->subject_id);

        $this->assertTrue($note->subjects->contains($subject));
        $this->assertTrue($subject->notes->contains($note));
    }

    /**
     * Test model scopes and methods.
     */
    public function test_model_scopes_and_methods(): void
    {
        $user = User::factory()->create();
        
        // Create published and draft notes
        $publishedNote = Note::create([
            'user_id' => $user->user_id,
            'title' => 'Published Note',
            'content' => 'Published content',
            'status' => 'published',
        ]);
        
        $draftNote = Note::create([
            'user_id' => $user->user_id,
            'title' => 'Draft Note',
            'content' => 'Draft content',
            'status' => 'draft',
        ]);

        // Test scopes
        $this->assertCount(1, Note::published()->get());
        $this->assertCount(1, Note::draft()->get());
        
        // Test methods
        $this->assertStringContainsString('Published content', $publishedNote->excerpt);
        $this->assertGreaterThan(0, $publishedNote->word_count);
        
        // Create AI and manual questions
        $aiQuestion = Question::create([
            'note_id' => $publishedNote->note_id,
            'user_id' => $user->user_id,
            'question_text' => 'AI generated question?',
            'generated_by' => 'AI',
        ]);
        
        $manualQuestion = Question::create([
            'note_id' => $publishedNote->note_id,
            'user_id' => $user->user_id,
            'question_text' => 'Manual question?',
            'generated_by' => 'Manual',
        ]);

        // Test question scopes
        $this->assertCount(1, Question::aiGenerated()->get());
        $this->assertCount(1, Question::manual()->get());
        $this->assertTrue($aiQuestion->isAiGenerated());
        $this->assertFalse($manualQuestion->isAiGenerated());
    }
}
