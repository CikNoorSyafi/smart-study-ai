<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\User;
use App\Models\Subject;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\SimpleQuestionGenerator;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Note::with(['user', 'subjects', 'questions']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by subject
        if ($request->filled('subject_id')) {
            $query->whereHas('subjects', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        $notes = $query->orderBy('created_at', 'desc')->paginate(15);

        // For filters
        $users = User::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('admin.notes.index', compact('notes', 'users', 'subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('admin.notes.create', compact('users', 'subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published,archived',
            'subjects' => 'array',
            'subjects.*' => 'exists:subjects,subject_id',
        ]);

        $note = Note::create([
            'note_id' => (string) Str::uuid(),
            'user_id' => $validated['user_id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => $validated['status'],
        ]);

        // Attach subjects if provided
        if (!empty($validated['subjects'])) {
            $note->subjects()->attach($validated['subjects']);
        }

        return redirect()->route('admin.notes-crud.index')
                        ->with('success', 'Note created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        $note->load(['user.profile', 'subjects', 'questions.answers']);

        $stats = [
            'questions_count' => $note->questions()->count(),
            'answers_count' => $note->questions()->withCount('answers')->get()->sum('answers_count'),
            'word_count' => $note->word_count,
            'character_count' => strlen($note->content),
        ];

        return view('admin.notes.show', compact('note', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        $users = User::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $noteSubjects = $note->subjects->pluck('subject_id')->toArray();

        return view('admin.notes.edit', compact('note', 'users', 'subjects', 'noteSubjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published,archived',
            'subjects' => 'array',
            'subjects.*' => 'exists:subjects,subject_id',
        ]);

        $note->update([
            'user_id' => $validated['user_id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => $validated['status'],
        ]);

        // Sync subjects
        if (isset($validated['subjects'])) {
            $note->subjects()->sync($validated['subjects']);
        } else {
            $note->subjects()->detach();
        }

        return redirect()->route('admin.notes-crud.index')
                        ->with('success', 'Note updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        $note->delete();

        return redirect()->route('admin.notes-crud.index')
                        ->with('success', 'Note deleted successfully.');
    }

    /**
     * Generate intelligent questions from note content
     */
    public function generateQuestions(Request $request, Note $note)
    {
        $request->validate([
            'count' => 'nullable|integer|min:1|max:10',
            'difficulty' => 'nullable|in:easy,medium,hard'
        ]);

        $count = $request->get('count', 5);
        $difficulty = $request->get('difficulty', 'medium');

        try {
            $generator = new SimpleQuestionGenerator();
            $questions = $generator->generateFromNote($note, $count, $difficulty);

            if (empty($questions)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to generate questions from this note content. Please ensure the note has sufficient content.'
                ], 400);
            }

            $questionData = collect($questions)->map(function($question) {
                return [
                    'id' => $question->question_id,
                    'text' => $question->question_text,
                    'type' => $question->question_type,
                    'difficulty' => $question->difficulty,
                    'explanation' => $question->explanation,
                    'answers' => $question->answers->map(function($answer) {
                        return [
                            'id' => $answer->answer_id,
                            'text' => $answer->answer_text,
                            'is_correct' => $answer->is_correct,
                            'explanation' => $answer->explanation
                        ];
                    }),
                    'status' => $question->status
                ];
            });

            return response()->json([
                'success' => true,
                'message' => count($questions) . ' questions generated successfully! Please review and approve them.',
                'questions' => $questionData,
                'requires_verification' => true
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate questions. Please try again.'
            ], 500);
        }
    }

    /**
     * Approve generated questions
     */
    public function approveQuestions(Request $request)
    {
        $request->validate([
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:questions,question_id'
        ]);

        $approvedCount = 0;
        foreach ($request->question_ids as $questionId) {
            $question = Question::where('question_id', $questionId)->first();
            if ($question && $question->status === 'pending') {
                $question->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                    'approved_by' => auth()->id()
                ]);
                $approvedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$approvedCount} questions approved successfully!"
        ]);
    }

    /**
     * Reject generated questions
     */
    public function rejectQuestions(Request $request)
    {
        $request->validate([
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:questions,question_id'
        ]);

        try {
            $rejectedCount = 0;
            foreach ($request->question_ids as $questionId) {
                $question = Question::where('question_id', $questionId)->first();

                if ($question && $question->status === 'pending') {
                    $question->answers()->delete();
                    $question->delete();
                    $rejectedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "{$rejectedCount} questions rejected and removed!"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject questions. Please try again.'
            ], 500);
        }
    }

    /**
     * Bulk update note status.
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'note_ids' => 'required|array',
            'note_ids.*' => 'exists:notes,note_id',
            'status' => 'required|in:draft,published,archived',
        ]);

        Note::whereIn('note_id', $validated['note_ids'])
            ->update(['status' => $validated['status']]);

        return redirect()->back()
                        ->with('success', 'Notes status updated successfully.');
    }


}
