<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Note;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Subject;
use App\Models\User;
use App\Services\PdfTextExtractor;
use App\Services\QuestionGenerator;
use App\Services\GroqQuestionGenerator;

class PdfUploadController extends Controller
{
    protected $pdfExtractor;
    protected $questionGenerator;
    protected $groqGenerator;

    public function __construct(PdfTextExtractor $pdfExtractor, QuestionGenerator $questionGenerator, GroqQuestionGenerator $groqGenerator)
    {
        $this->pdfExtractor = $pdfExtractor;
        $this->questionGenerator = $questionGenerator;
        $this->groqGenerator = $groqGenerator;
    }

    /**
     * Show the PDF upload form
     */
    public function index()
    {
        $subjects = Subject::all();
        return view('pdf-upload.index', compact('subjects'));
    }

    /**
     * Handle PDF upload and generate questions
     */
    public function upload(Request $request)
    {
        $request->validate([
            'pdf_file' => 'required|file|mimes:pdf|max:2048', // 2MB max
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,subject_id',
            'difficulty' => 'required|in:easy,medium,hard',
            'question_count' => 'required|integer|min:1|max:50',
            'question_types' => 'required|array',
            'question_types.*' => 'in:multiple_choice,true_false,short_answer,essay',
            'generator_type' => 'required|in:local,groq',
        ]);

        try {
            // Ensure we have a valid user in session
            $userId = $this->ensureValidUser();
            // Store the uploaded PDF
            $file = $request->file('pdf_file');
            $filename = time() . '_' . Str::slug($request->title) . '.pdf';
            $filePath = $file->storeAs('pdfs', $filename, 'public');

            // Extract text from PDF
            $extractedText = $this->pdfExtractor->extractText(storage_path('app/public/' . $filePath));

            if (empty($extractedText)) {
                return back()->with('error', 'Could not extract text from the PDF. Please ensure the PDF contains readable text.');
            }

            // Create a note from the extracted content
            $note = Note::create([
                'note_id' => (string) Str::uuid(),
                'user_id' => $userId,
                'title' => $request->title,
                'content' => $extractedText,
                'status' => 'published',
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
            ]);

            // Associate with subject
            $note->subjects()->attach($request->subject_id);

            // Select generator based on user choice
            $generator = $request->generator_type === 'groq' ? $this->groqGenerator : $this->questionGenerator;

            // Generate questions and answers
            $generatedQuestions = $generator->generateQuestions(
                $extractedText,
                $request->question_count,
                $request->difficulty,
                $request->question_types
            );

            $createdQuestions = [];
            foreach ($generatedQuestions as $questionData) {
                $question = Question::create([
                    'question_id' => (string) Str::uuid(),
                    'note_id' => $note->note_id,
                    'user_id' => $userId,
                    'question_text' => $questionData['question'],
                    'difficulty' => $request->difficulty,
                    'generated_by' => $request->generator_type === 'groq' ? 'Groq AI' : 'Local AI',
                ]);

                // Create answers
                foreach ($questionData['answers'] as $answerData) {
                    Answer::create([
                        'answer_id' => (string) Str::uuid(),
                        'question_id' => $question->question_id,
                        'answer_text' => $answerData['text'],
                        'is_correct' => $answerData['is_correct'],
                    ]);
                }

                $createdQuestions[] = $question;
            }

            return redirect()->route('pdf-upload.result', $note->note_id)
                           ->with('success', "Successfully generated {$request->question_count} questions from your PDF!");

        } catch (\Exception $e) {
            // Clean up uploaded file if something went wrong
            if (isset($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return back()->with('error', 'An error occurred while processing your PDF: ' . $e->getMessage());
        }
    }

    /**
     * Show the results of PDF processing
     */
    public function result($noteId)
    {
        $note = Note::with(['questions.answers', 'subjects'])->findOrFail($noteId);
        
        $stats = [
            'questions_generated' => $note->questions->count(),
            'total_answers' => $note->questions->sum(function($question) {
                return $question->answers->where('is_correct', true)->count();
            }),
            'difficulty_breakdown' => $note->questions->groupBy('difficulty')->map->count(),
            'file_size' => $note->file_path ? Storage::disk('public')->size($note->file_path) : 0,
        ];

        return view('pdf-upload.result', compact('note', 'stats'));
    }

    /**
     * Download the original PDF
     */
    public function download($noteId)
    {
        $note = Note::findOrFail($noteId);
        
        if (!$note->file_path || !Storage::disk('public')->exists($note->file_path)) {
            return back()->with('error', 'PDF file not found.');
        }

        return Storage::disk('public')->download($note->file_path, $note->file_name);
    }

    /**
     * Delete uploaded PDF and associated data
     */
    public function delete($noteId)
    {
        $note = Note::with(['questions.answers'])->findOrFail($noteId);

        // Delete associated answers
        foreach ($note->questions as $question) {
            $question->answers()->delete();
        }

        // Delete questions
        $note->questions()->delete();

        // Delete PDF file
        if ($note->file_path && Storage::disk('public')->exists($note->file_path)) {
            Storage::disk('public')->delete($note->file_path);
        }

        // Delete note
        $note->delete();

        return redirect()->route('pdf-upload.index')
                       ->with('success', 'PDF and all associated questions have been deleted.');
    }

    /**
     * List all uploaded PDFs
     */
    public function list()
    {
        $notes = Note::whereNotNull('file_path')
                    ->with(['subjects', 'questions'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return view('pdf-upload.list', compact('notes'));
    }

    /**
     * Ensure we have a valid user in session, create one if needed
     * Prioritize admin and teacher users for PDF upload functionality
     */
    private function ensureValidUser()
    {
        $sessionUserId = session('user.id');

        // Check if session user exists in database and has appropriate role
        if ($sessionUserId) {
            $sessionUser = User::find($sessionUserId);
            if ($sessionUser && in_array($sessionUser->role, ['admin', 'teacher'])) {
                return $sessionUserId;
            }
        }

        // Session user doesn't exist or doesn't have appropriate role
        // Find the first admin user
        $user = User::where('role', 'admin')->first();

        if (!$user) {
            // No admin users exist, find the first teacher
            $user = User::where('role', 'teacher')->first();
        }

        if (!$user) {
            // No admin or teacher users exist, create a default admin user
            $user = User::create([
                'user_id' => (string) Str::uuid(),
                'name' => 'System Admin',
                'email' => 'system@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
        }

        // Update session with valid user
        session([
            'user' => [
                'id' => $user->user_id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ]
        ]);

        return $user->user_id;
    }
}
