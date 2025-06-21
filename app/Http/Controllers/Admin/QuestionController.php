<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Note;
use App\Models\User;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Question::with(['note', 'user', 'answers']);

        // Filter by status (default to approved only)
        $status = $request->get('status', 'approved');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('question_text', 'like', "%{$search}%");
        }

        // Filter by difficulty
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        // Filter by generation method
        if ($request->filled('generated_by')) {
            $query->where('generated_by', $request->generated_by);
        }

        // Filter by note
        if ($request->filled('note_id')) {
            $query->where('note_id', $request->note_id);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $questions = $query->orderBy('created_at', 'desc')->paginate(15);

        // For filters
        $notes = Note::orderBy('title')->get();
        $users = User::orderBy('name')->get();
        $statuses = ['approved', 'pending', 'all'];

        return view('admin.questions.index', compact('questions', 'notes', 'users', 'statuses', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $notes = Note::orderBy('title')->get();
        $users = User::orderBy('name')->get();

        return view('admin.questions.create', compact('notes', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'note_id' => 'required|exists:notes,note_id',
            'user_id' => 'required|exists:users,user_id',
            'question_text' => 'required|string',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'generated_by' => 'required|in:AI,Manual',
            'answers' => 'required|array|min:1',
            'answers.*.text' => 'required|string',
            'answers.*.is_correct' => 'boolean',
        ]);

        $question = Question::create([
            'question_id' => (string) Str::uuid(),
            'note_id' => $validated['note_id'],
            'user_id' => $validated['user_id'],
            'question_text' => $validated['question_text'],
            'difficulty' => $validated['difficulty'],
            'generated_by' => $validated['generated_by'],
        ]);

        // Create answers
        foreach ($validated['answers'] as $answerData) {
            Answer::create([
                'answer_id' => (string) Str::uuid(),
                'question_id' => $question->question_id,
                'answer_text' => $answerData['text'],
                'is_correct' => $answerData['is_correct'] ?? false,
            ]);
        }

        return redirect()->route('admin.questions.index')
                        ->with('success', 'Question created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        $question->load(['note', 'user.profile', 'answers', 'feedback.user']);

        $stats = [
            'answers_count' => $question->answers()->count(),
            'correct_answers' => $question->answers()->where('is_correct', true)->count(),
            'feedback_count' => $question->feedback()->count(),
            'avg_rating' => $question->feedback()->avg('rating'),
        ];

        return view('admin.questions.show', compact('question', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        $question->load('answers');
        $notes = Note::orderBy('title')->get();
        $users = User::orderBy('name')->get();

        return view('admin.questions.edit', compact('question', 'notes', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'note_id' => 'required|exists:notes,note_id',
            'user_id' => 'required|exists:users,user_id',
            'question_text' => 'required|string',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'generated_by' => 'required|in:AI,Manual',
            'answers' => 'required|array|min:1',
            'answers.*.id' => 'nullable|exists:answers,answer_id',
            'answers.*.text' => 'required|string',
            'answers.*.is_correct' => 'boolean',
        ]);

        $question->update([
            'note_id' => $validated['note_id'],
            'user_id' => $validated['user_id'],
            'question_text' => $validated['question_text'],
            'difficulty' => $validated['difficulty'],
            'generated_by' => $validated['generated_by'],
        ]);

        // Update answers
        $existingAnswerIds = [];
        foreach ($validated['answers'] as $answerData) {
            if (!empty($answerData['id'])) {
                // Update existing answer
                $answer = Answer::find($answerData['id']);
                if ($answer && $answer->question_id === $question->question_id) {
                    $answer->update([
                        'answer_text' => $answerData['text'],
                        'is_correct' => $answerData['is_correct'] ?? false,
                    ]);
                    $existingAnswerIds[] = $answer->answer_id;
                }
            } else {
                // Create new answer
                $newAnswer = Answer::create([
                    'answer_id' => (string) Str::uuid(),
                    'question_id' => $question->question_id,
                    'answer_text' => $answerData['text'],
                    'is_correct' => $answerData['is_correct'] ?? false,
                ]);
                $existingAnswerIds[] = $newAnswer->answer_id;
            }
        }

        // Delete removed answers
        $question->answers()->whereNotIn('answer_id', $existingAnswerIds)->delete();

        return redirect()->route('admin.questions.index')
                        ->with('success', 'Question updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $question->delete();

        return redirect()->route('admin.questions.index')
                        ->with('success', 'Question deleted successfully.');
    }
}
