<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subject;
use App\Models\Note;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Display the teacher dashboard.
     */
    public function index()
    {
        $teacher = auth()->user();
        
        // Get teacher's assigned subjects using Eloquent for proper model instances
        $subjectIds = DB::table('user_subjects')
            ->where('user_id', $teacher->user_id)
            ->where('role_in_subject', 'teacher')
            ->pluck('subject_id');

        $teacherSubjects = Subject::whereIn('subject_id', $subjectIds)->get();

        // Add counts manually for each subject
        foreach ($teacherSubjects as $subject) {
            $subject->users_count = DB::table('user_subjects')
                ->where('subject_id', $subject->subject_id)
                ->where('role_in_subject', 'student')
                ->count();

            $subject->notes_count = DB::table('note_subjects')
                ->where('subject_id', $subject->subject_id)
                ->count();
        }

        // Get students assigned to teacher's subjects
        $studentIds = DB::table('user_subjects')
            ->whereIn('subject_id', $teacherSubjects->pluck('subject_id'))
            ->where('role_in_subject', 'student')
            ->pluck('user_id')
            ->unique();

        $students = User::whereIn('user_id', $studentIds)
            ->where('is_active', true)
            ->get();

        // Get teacher's notes
        $teacherNotes = Note::where('user_id', $teacher->user_id)
            ->with(['subjects'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get questions from teacher's notes
        $teacherQuestions = Question::whereHas('note', function($query) use ($teacher) {
            $query->where('user_id', $teacher->user_id);
        })->with(['note', 'answers'])->take(5)->get();

        // Calculate statistics
        $stats = [
            'total_subjects' => $teacherSubjects->count(),
            'total_students' => $students->count(),
            'total_notes' => Note::where('user_id', $teacher->user_id)->count(),
            'total_questions' => Question::whereHas('note', function($query) use ($teacher) {
                $query->where('user_id', $teacher->user_id);
            })->count(),
            'published_notes' => Note::where('user_id', $teacher->user_id)
                ->where('status', 'published')->count(),
            'draft_notes' => Note::where('user_id', $teacher->user_id)
                ->where('status', 'draft')->count(),
        ];

        // Recent activity
        $recentActivity = [
            [
                'type' => 'note_created',
                'description' => 'Created new study note',
                'time' => '2 hours ago',
                'icon' => 'fas fa-file-alt',
                'color' => 'text-blue-600'
            ],
            [
                'type' => 'student_joined',
                'description' => 'New student joined Mathematics',
                'time' => '1 day ago',
                'icon' => 'fas fa-user-plus',
                'color' => 'text-green-600'
            ],
            [
                'type' => 'question_generated',
                'description' => 'Generated 5 new questions',
                'time' => '2 days ago',
                'icon' => 'fas fa-question-circle',
                'color' => 'text-purple-600'
            ],
        ];

        return view('teacher.dashboard', compact(
            'teacher',
            'teacherSubjects',
            'students',
            'teacherNotes',
            'teacherQuestions',
            'stats',
            'recentActivity'
        ));
    }

    /**
     * Get teacher's subjects
     */
    public function subjects()
    {
        $teacher = auth()->user();

        // Get subject IDs where this user is a teacher
        $subjectIds = DB::table('user_subjects')
            ->where('user_id', $teacher->user_id)
            ->where('role_in_subject', 'teacher')
            ->pluck('subject_id');

        // Get subjects using Eloquent for proper Carbon instances
        $subjects = Subject::whereIn('subject_id', $subjectIds)
            ->paginate(10);

        // Add counts manually for each subject
        foreach ($subjects as $subject) {
            $subject->student_count = DB::table('user_subjects')
                ->where('subject_id', $subject->subject_id)
                ->where('role_in_subject', 'student')
                ->count();

            $subject->notes_count = DB::table('note_subjects')
                ->where('subject_id', $subject->subject_id)
                ->count();
        }

        return view('teacher.subjects.index', compact('subjects'));
    }

    /**
     * Get teacher's students
     */
    public function students()
    {
        $teacher = auth()->user();

        // Get all students from teacher's subjects using a more explicit query
        $subjectIds = DB::table('user_subjects')
            ->where('user_id', $teacher->user_id)
            ->where('role_in_subject', 'teacher')
            ->pluck('subject_id');

        $students = User::whereHas('subjects', function($query) use ($subjectIds) {
            $query->whereIn('user_subjects.subject_id', $subjectIds)
                  ->where('user_subjects.role_in_subject', 'student');
        })->with(['subjects' => function($query) use ($subjectIds) {
            $query->whereIn('user_subjects.subject_id', $subjectIds);
        }])->paginate(15);

        return view('teacher.students.index', compact('students'));
    }

    /**
     * Get teacher's content (notes and questions)
     */
    public function content()
    {
        $teacher = auth()->user();
        
        $notes = Note::where('user_id', $teacher->user_id)
            ->with(['subjects'])
            ->withCount(['questions'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('teacher.content.index', compact('notes'));
    }

    /**
     * Teacher analytics
     */
    public function analytics()
    {
        $teacher = auth()->user();

        // Subject performance data using explicit queries
        $subjectStats = DB::table('subjects')
            ->join('user_subjects', 'subjects.subject_id', '=', 'user_subjects.subject_id')
            ->where('user_subjects.user_id', $teacher->user_id)
            ->where('user_subjects.role_in_subject', 'teacher')
            ->select('subjects.*')
            ->get();

        // Add counts manually for each subject
        foreach ($subjectStats as $subject) {
            $subject->student_count = DB::table('user_subjects')
                ->where('subject_id', $subject->subject_id)
                ->where('role_in_subject', 'student')
                ->count();

            $subject->notes_count = DB::table('note_subjects')
                ->where('subject_id', $subject->subject_id)
                ->count();
        }

        // Monthly content creation
        $monthlyNotes = Note::where('user_id', $teacher->user_id)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Fill missing months with 0
        for ($i = 1; $i <= 12; $i++) {
            if (!isset($monthlyNotes[$i])) {
                $monthlyNotes[$i] = 0;
            }
        }
        ksort($monthlyNotes);

        return view('teacher.analytics', compact('subjectStats', 'monthlyNotes'));
    }

    // ==========================================
    // NOTES CRUD OPERATIONS
    // ==========================================

    /**
     * Show the form for creating a new note
     */
    public function createNote()
    {
        $teacher = auth()->user();

        // Get teacher's subjects for assignment
        $subjects = DB::table('subjects')
            ->join('user_subjects', 'subjects.subject_id', '=', 'user_subjects.subject_id')
            ->where('user_subjects.user_id', $teacher->user_id)
            ->where('user_subjects.role_in_subject', 'teacher')
            ->select('subjects.*')
            ->get();

        return view('teacher.notes.create', compact('subjects'));
    }

    /**
     * Store a newly created note
     */
    public function storeNote(Request $request)
    {
        $teacher = auth()->user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'subjects' => 'array',
            'subjects.*' => 'exists:subjects,subject_id'
        ]);

        // Create the note
        $note = Note::create([
            'note_id' => (string) Str::uuid(),
            'user_id' => $teacher->user_id,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => $validated['status'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Attach subjects if provided
        if (!empty($validated['subjects'])) {
            foreach ($validated['subjects'] as $subjectId) {
                DB::table('note_subjects')->insert([
                    'note_id' => $note->note_id,
                    'subject_id' => $subjectId,
                ]);
            }
        }

        return redirect()->route('teacher.content')
            ->with('success', 'Study note created successfully!');
    }

    /**
     * Display the specified note
     */
    public function showNote(Note $note)
    {
        $teacher = auth()->user();

        // Ensure teacher owns this note
        if ($note->user_id !== $teacher->user_id) {
            abort(403, 'Unauthorized access to this note.');
        }

        $note->load(['subjects', 'questions.answers']);

        return view('teacher.notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified note
     */
    public function editNote(Note $note)
    {
        $teacher = auth()->user();

        // Ensure teacher owns this note
        if ($note->user_id !== $teacher->user_id) {
            abort(403, 'Unauthorized access to this note.');
        }

        // Get teacher's subjects for assignment
        $subjects = DB::table('subjects')
            ->join('user_subjects', 'subjects.subject_id', '=', 'user_subjects.subject_id')
            ->where('user_subjects.user_id', $teacher->user_id)
            ->where('user_subjects.role_in_subject', 'teacher')
            ->select('subjects.*')
            ->get();

        $note->load('subjects');

        return view('teacher.notes.edit', compact('note', 'subjects'));
    }

    /**
     * Update the specified note
     */
    public function updateNote(Request $request, Note $note)
    {
        $teacher = auth()->user();

        // Ensure teacher owns this note
        if ($note->user_id !== $teacher->user_id) {
            abort(403, 'Unauthorized access to this note.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'subjects' => 'array',
            'subjects.*' => 'exists:subjects,subject_id'
        ]);

        // Update the note
        $note->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => $validated['status'],
            'updated_at' => now(),
        ]);

        // Update subject associations
        DB::table('note_subjects')->where('note_id', $note->note_id)->delete();

        if (!empty($validated['subjects'])) {
            foreach ($validated['subjects'] as $subjectId) {
                DB::table('note_subjects')->insert([
                    'note_id' => $note->note_id,
                    'subject_id' => $subjectId,
                ]);
            }
        }

        return redirect()->route('teacher.content')
            ->with('success', 'Study note updated successfully!');
    }

    /**
     * Remove the specified note
     */
    public function deleteNote(Note $note)
    {
        $teacher = auth()->user();

        // Ensure teacher owns this note
        if ($note->user_id !== $teacher->user_id) {
            abort(403, 'Unauthorized access to this note.');
        }

        // Delete related data
        DB::table('note_subjects')->where('note_id', $note->note_id)->delete();

        // Delete questions and their answers
        $questions = Question::where('note_id', $note->note_id)->get();
        foreach ($questions as $question) {
            Answer::where('question_id', $question->question_id)->delete();
            $question->delete();
        }

        // Delete the note
        $note->delete();

        return redirect()->route('teacher.content')
            ->with('success', 'Study note deleted successfully!');
    }

    // ==========================================
    // QUESTIONS CRUD OPERATIONS
    // ==========================================

    /**
     * Show the form for creating a new question
     */
    public function createQuestion(Request $request)
    {
        $teacher = auth()->user();

        // Get teacher's notes for question assignment
        $notes = Note::where('user_id', $teacher->user_id)->get();

        // Pre-select note if provided
        $selectedNoteId = $request->get('note_id');

        return view('teacher.questions.create', compact('notes', 'selectedNoteId'));
    }

    /**
     * Store a newly created question
     */
    public function storeQuestion(Request $request)
    {
        $teacher = auth()->user();

        $validated = $request->validate([
            'note_id' => 'required|exists:notes,note_id',
            'question_text' => 'required|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'answers' => 'required|array|min:2|max:6',
            'answers.*.text' => 'required|string',
            'answers.*.is_correct' => 'boolean',
        ]);

        // Verify teacher owns the note
        $note = Note::where('note_id', $validated['note_id'])
                   ->where('user_id', $teacher->user_id)
                   ->firstOrFail();

        // Create the question
        $question = Question::create([
            'question_id' => (string) Str::uuid(),
            'note_id' => $validated['note_id'],
            'question_text' => $validated['question_text'],
            'difficulty' => $validated['difficulty'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create answers
        foreach ($validated['answers'] as $answerData) {
            Answer::create([
                'answer_id' => (string) Str::uuid(),
                'question_id' => $question->question_id,
                'answer_text' => $answerData['text'],
                'is_correct' => isset($answerData['is_correct']) && $answerData['is_correct'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('teacher.notes.show', $note)
            ->with('success', 'Question created successfully!');
    }

    /**
     * Show the form for editing the specified question
     */
    public function editQuestion(Question $question)
    {
        $teacher = auth()->user();

        // Ensure teacher owns this question through the note
        $note = Note::where('note_id', $question->note_id)
                   ->where('user_id', $teacher->user_id)
                   ->firstOrFail();

        $question->load('answers');

        return view('teacher.questions.edit', compact('question', 'note'));
    }

    /**
     * Update the specified question
     */
    public function updateQuestion(Request $request, Question $question)
    {
        $teacher = auth()->user();

        // Ensure teacher owns this question through the note
        $note = Note::where('note_id', $question->note_id)
                   ->where('user_id', $teacher->user_id)
                   ->firstOrFail();

        $validated = $request->validate([
            'question_text' => 'required|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'answers' => 'required|array|min:2|max:6',
            'answers.*.text' => 'required|string',
            'answers.*.is_correct' => 'boolean',
        ]);

        // Update the question
        $question->update([
            'question_text' => $validated['question_text'],
            'difficulty' => $validated['difficulty'],
            'updated_at' => now(),
        ]);

        // Delete existing answers and create new ones
        Answer::where('question_id', $question->question_id)->delete();

        foreach ($validated['answers'] as $answerData) {
            Answer::create([
                'answer_id' => (string) Str::uuid(),
                'question_id' => $question->question_id,
                'answer_text' => $answerData['text'],
                'is_correct' => isset($answerData['is_correct']) && $answerData['is_correct'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('teacher.notes.show', $note)
            ->with('success', 'Question updated successfully!');
    }

    /**
     * Remove the specified question
     */
    public function deleteQuestion(Question $question)
    {
        $teacher = auth()->user();

        // Ensure teacher owns this question through the note
        $note = Note::where('note_id', $question->note_id)
                   ->where('user_id', $teacher->user_id)
                   ->firstOrFail();

        // Delete answers first
        Answer::where('question_id', $question->question_id)->delete();

        // Delete the question
        $question->delete();

        return redirect()->route('teacher.notes.show', $note)
            ->with('success', 'Question deleted successfully!');
    }

    // ==========================================
    // BULK OPERATIONS
    // ==========================================

    /**
     * Bulk delete notes
     */
    public function bulkDeleteNotes(Request $request)
    {
        $teacher = auth()->user();

        $validated = $request->validate([
            'note_ids' => 'required|array',
            'note_ids.*' => 'exists:notes,note_id'
        ]);

        $deletedCount = 0;

        foreach ($validated['note_ids'] as $noteId) {
            $note = Note::where('note_id', $noteId)
                       ->where('user_id', $teacher->user_id)
                       ->first();

            if ($note) {
                // Delete related data
                DB::table('note_subjects')->where('note_id', $note->note_id)->delete();

                // Delete questions and their answers
                $questions = Question::where('note_id', $note->note_id)->get();
                foreach ($questions as $question) {
                    Answer::where('question_id', $question->question_id)->delete();
                    $question->delete();
                }

                // Delete the note
                $note->delete();
                $deletedCount++;
            }
        }

        return redirect()->route('teacher.content')
            ->with('success', "Successfully deleted {$deletedCount} notes.");
    }

    /**
     * Bulk delete questions
     */
    public function bulkDeleteQuestions(Request $request)
    {
        $teacher = auth()->user();

        $validated = $request->validate([
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:questions,question_id'
        ]);

        $deletedCount = 0;

        foreach ($validated['question_ids'] as $questionId) {
            $question = Question::where('question_id', $questionId)->first();

            if ($question) {
                // Verify teacher owns this question through the note
                $note = Note::where('note_id', $question->note_id)
                           ->where('user_id', $teacher->user_id)
                           ->first();

                if ($note) {
                    // Delete answers first
                    Answer::where('question_id', $question->question_id)->delete();

                    // Delete the question
                    $question->delete();
                    $deletedCount++;
                }
            }
        }

        return redirect()->back()
            ->with('success', "Successfully deleted {$deletedCount} questions.");
    }

    // ==========================================
    // STUDENT MANAGEMENT OPERATIONS
    // ==========================================

    /**
     * Assign a student to teacher's subject
     */
    public function assignStudent(Request $request, User $student)
    {
        $teacher = auth()->user();

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,subject_id'
        ]);

        // Verify teacher teaches this subject
        $teacherSubject = DB::table('user_subjects')
            ->where('user_id', $teacher->user_id)
            ->where('subject_id', $validated['subject_id'])
            ->where('role_in_subject', 'teacher')
            ->first();

        if (!$teacherSubject) {
            return redirect()->back()
                ->with('error', 'You are not authorized to assign students to this subject.');
        }

        // Check if student is already assigned
        $existingAssignment = DB::table('user_subjects')
            ->where('user_id', $student->user_id)
            ->where('subject_id', $validated['subject_id'])
            ->where('role_in_subject', 'student')
            ->first();

        if ($existingAssignment) {
            return redirect()->back()
                ->with('error', 'Student is already assigned to this subject.');
        }

        // Assign student to subject
        DB::table('user_subjects')->insert([
            'user_id' => $student->user_id,
            'subject_id' => $validated['subject_id'],
            'role_in_subject' => 'student',
        ]);

        return redirect()->back()
            ->with('success', "Successfully assigned {$student->name} to the subject.");
    }

    /**
     * Remove a student from teacher's subject
     */
    public function removeStudent(Request $request, User $student)
    {
        $teacher = auth()->user();

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,subject_id'
        ]);

        // Verify teacher teaches this subject
        $teacherSubject = DB::table('user_subjects')
            ->where('user_id', $teacher->user_id)
            ->where('subject_id', $validated['subject_id'])
            ->where('role_in_subject', 'teacher')
            ->first();

        if (!$teacherSubject) {
            return redirect()->back()
                ->with('error', 'You are not authorized to remove students from this subject.');
        }

        // Remove student from subject
        $removed = DB::table('user_subjects')
            ->where('user_id', $student->user_id)
            ->where('subject_id', $validated['subject_id'])
            ->where('role_in_subject', 'student')
            ->delete();

        if ($removed) {
            return redirect()->back()
                ->with('success', "Successfully removed {$student->name} from the subject.");
        } else {
            return redirect()->back()
                ->with('error', 'Student was not assigned to this subject.');
        }
    }

    // ==========================================
    // PHASE 2: HUB PAGES
    // ==========================================

    /**
     * Content Creation Hub
     */
    public function createHub()
    {
        $teacher = auth()->user();

        // Get teacher's subjects for context
        $teacherSubjects = DB::table('subjects')
            ->join('user_subjects', 'subjects.subject_id', '=', 'user_subjects.subject_id')
            ->where('user_subjects.user_id', $teacher->user_id)
            ->where('user_subjects.role_in_subject', 'teacher')
            ->select('subjects.*')
            ->get();

        // Get recent content stats
        $contentStats = [
            'total_notes' => Note::where('user_id', $teacher->user_id)->count(),
            'total_questions' => Question::whereHas('note', function($query) use ($teacher) {
                $query->where('user_id', $teacher->user_id);
            })->count(),
            'draft_notes' => Note::where('user_id', $teacher->user_id)->where('status', 'draft')->count(),
            'published_notes' => Note::where('user_id', $teacher->user_id)->where('status', 'published')->count(),
        ];

        // Get recent notes for templates
        $recentNotes = Note::where('user_id', $teacher->user_id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('teacher.create.hub', compact('teacherSubjects', 'contentStats', 'recentNotes'));
    }

    /**
     * Student Management Hub
     */
    public function studentsHub()
    {
        $teacher = auth()->user();

        // Get teacher's subjects
        $subjectIds = DB::table('user_subjects')
            ->where('user_id', $teacher->user_id)
            ->where('role_in_subject', 'teacher')
            ->pluck('subject_id');

        $teacherSubjects = Subject::whereIn('subject_id', $subjectIds)->get();

        // Get students with detailed info
        $students = User::whereHas('subjects', function($query) use ($subjectIds) {
            $query->whereIn('user_subjects.subject_id', $subjectIds)
                  ->where('user_subjects.role_in_subject', 'student');
        })->with(['subjects' => function($query) use ($subjectIds) {
            $query->whereIn('user_subjects.subject_id', $subjectIds);
        }])->get();

        // Calculate student statistics
        $studentStats = [
            'total_students' => $students->count(),
            'active_students' => $students->where('is_active', true)->count(),
            'students_this_week' => $students->where('created_at', '>=', now()->subWeek())->count(),
            'avg_progress' => rand(70, 85), // TODO: Calculate real progress
        ];

        // Get subject-wise student distribution
        $subjectDistribution = [];
        foreach ($teacherSubjects as $subject) {
            $subjectStudentCount = DB::table('user_subjects')
                ->where('subject_id', $subject->subject_id)
                ->where('role_in_subject', 'student')
                ->count();

            $subjectDistribution[] = [
                'subject' => $subject,
                'student_count' => $subjectStudentCount,
                'percentage' => $students->count() > 0 ? round(($subjectStudentCount / $students->count()) * 100, 1) : 0
            ];
        }

        // Get unassigned students (students not in any of teacher's subjects)
        $allStudents = User::where('role', 'student')->get();
        $assignedStudentIds = $students->pluck('user_id');
        $unassignedStudents = $allStudents->whereNotIn('user_id', $assignedStudentIds);

        return view('teacher.students.hub', compact(
            'teacherSubjects',
            'students',
            'studentStats',
            'subjectDistribution',
            'unassignedStudents'
        ));
    }

    // ==========================================
    // NOTIFICATION SYSTEM
    // ==========================================

    /**
     * Get notifications for the authenticated teacher
     */
    public function getNotifications(Request $request)
    {
        $teacher = auth()->user();

        $notifications = Notification::where('user_id', $teacher->user_id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $unreadCount = Notification::where('user_id', $teacher->user_id)
            ->unread()
            ->count();

        if ($request->ajax()) {
            return response()->json([
                'notifications' => $notifications,
                'unread_count' => $unreadCount,
                'html' => view('teacher.partials.notifications', compact('notifications'))->render()
            ]);
        }

        return view('teacher.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark a notification as read
     */
    public function markNotificationRead(Notification $notification)
    {
        $teacher = auth()->user();

        // Ensure teacher owns this notification
        if ($notification->user_id !== $teacher->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->markAsRead();

        $unreadCount = Notification::where('user_id', $teacher->user_id)
            ->unread()
            ->count();

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsRead()
    {
        $teacher = auth()->user();

        Notification::where('user_id', $teacher->user_id)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'unread_count' => 0
        ]);
    }

    /**
     * Delete a notification
     */
    public function deleteNotification(Notification $notification)
    {
        $teacher = auth()->user();

        // Ensure teacher owns this notification
        if ($notification->user_id !== $teacher->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->delete();

        $unreadCount = Notification::where('user_id', $teacher->user_id)
            ->unread()
            ->count();

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount
        ]);
    }

    // ==========================================
    // SUBJECT DETAIL PAGES
    // ==========================================

    /**
     * Show subject detail page
     */
    public function subjectDetail(Subject $subject)
    {
        $teacher = auth()->user();

        // Verify teacher has access to this subject
        $hasAccess = DB::table('user_subjects')
            ->where('user_id', $teacher->user_id)
            ->where('subject_id', $subject->subject_id)
            ->where('role_in_subject', 'teacher')
            ->exists();

        if (!$hasAccess) {
            abort(403, 'You do not have access to this subject.');
        }

        // Get subject statistics
        $stats = [
            'total_students' => DB::table('user_subjects')
                ->where('subject_id', $subject->subject_id)
                ->where('role_in_subject', 'student')
                ->count(),
            'total_notes' => DB::table('note_subjects')
                ->join('notes', 'note_subjects.note_id', '=', 'notes.note_id')
                ->where('note_subjects.subject_id', $subject->subject_id)
                ->where('notes.user_id', $teacher->user_id)
                ->count(),
            'published_notes' => DB::table('note_subjects')
                ->join('notes', 'note_subjects.note_id', '=', 'notes.note_id')
                ->where('note_subjects.subject_id', $subject->subject_id)
                ->where('notes.user_id', $teacher->user_id)
                ->where('notes.status', 'published')
                ->count(),
            'total_questions' => DB::table('note_subjects')
                ->join('notes', 'note_subjects.note_id', '=', 'notes.note_id')
                ->join('questions', 'notes.note_id', '=', 'questions.note_id')
                ->where('note_subjects.subject_id', $subject->subject_id)
                ->where('notes.user_id', $teacher->user_id)
                ->count(),
        ];

        // Get recent students (last 5 enrolled)
        $recentStudents = User::whereHas('subjects', function($query) use ($subject) {
            $query->where('user_subjects.subject_id', $subject->subject_id)
                  ->where('user_subjects.role_in_subject', 'student');
        })->orderBy('created_at', 'desc')->take(5)->get();

        // Get recent notes for this subject
        $recentNotes = Note::whereHas('subjects', function($query) use ($subject) {
            $query->where('note_subjects.subject_id', $subject->subject_id);
        })->where('user_id', $teacher->user_id)
          ->orderBy('created_at', 'desc')
          ->take(5)
          ->get();

        // Get recent activity for this subject
        $recentActivity = [
            [
                'icon' => 'fas fa-user-plus',
                'color' => 'text-green-500',
                'description' => 'New student enrolled in ' . $subject->name,
                'time' => '2 hours ago'
            ],
            [
                'icon' => 'fas fa-file-alt',
                'color' => 'text-blue-500',
                'description' => 'Study note updated',
                'time' => '1 day ago'
            ],
            [
                'icon' => 'fas fa-question-circle',
                'color' => 'text-purple-500',
                'description' => 'Questions generated from notes',
                'time' => '2 days ago'
            ]
        ];

        return view('teacher.subjects.detail', compact(
            'subject',
            'stats',
            'recentStudents',
            'recentNotes',
            'recentActivity'
        ));
    }

    /**
     * Show subject students page
     */
    public function subjectStudents(Subject $subject)
    {
        $teacher = auth()->user();

        // Verify teacher has access to this subject
        $hasAccess = DB::table('user_subjects')
            ->where('user_id', $teacher->user_id)
            ->where('subject_id', $subject->subject_id)
            ->where('role_in_subject', 'teacher')
            ->exists();

        if (!$hasAccess) {
            abort(403, 'You do not have access to this subject.');
        }

        // Get all students for this subject
        $students = User::whereHas('subjects', function($query) use ($subject) {
            $query->where('user_subjects.subject_id', $subject->subject_id)
                  ->where('user_subjects.role_in_subject', 'student');
        })->with(['subjects' => function($query) use ($subject) {
            $query->where('user_subjects.subject_id', $subject->subject_id);
        }])->paginate(20);

        // Get unassigned students
        $assignedStudentIds = $students->pluck('user_id');
        $unassignedStudents = User::where('role', 'student')
            ->whereNotIn('user_id', $assignedStudentIds)
            ->take(10)
            ->get();

        return view('teacher.subjects.students', compact('subject', 'students', 'unassignedStudents'));
    }

    /**
     * Show subject content page
     */
    public function subjectContent(Subject $subject)
    {
        $teacher = auth()->user();

        // Verify teacher has access to this subject
        $hasAccess = DB::table('user_subjects')
            ->where('user_id', $teacher->user_id)
            ->where('subject_id', $subject->subject_id)
            ->where('role_in_subject', 'teacher')
            ->exists();

        if (!$hasAccess) {
            abort(403, 'You do not have access to this subject.');
        }

        // Get notes for this subject
        $notes = Note::whereHas('subjects', function($query) use ($subject) {
            $query->where('note_subjects.subject_id', $subject->subject_id);
        })->where('user_id', $teacher->user_id)
          ->with(['subjects'])
          ->orderBy('created_at', 'desc')
          ->paginate(10);

        // Get questions for this subject
        $questions = Question::whereHas('note.subjects', function($query) use ($subject) {
            $query->where('note_subjects.subject_id', $subject->subject_id);
        })->whereHas('note', function($query) use ($teacher) {
            $query->where('user_id', $teacher->user_id);
        })->with(['note', 'answers'])
          ->orderBy('created_at', 'desc')
          ->paginate(10);

        return view('teacher.subjects.content', compact('subject', 'notes', 'questions'));
    }

    /**
     * Show subject analytics page
     */
    public function subjectAnalytics(Subject $subject)
    {
        $teacher = auth()->user();

        // Verify teacher has access to this subject
        $hasAccess = DB::table('user_subjects')
            ->where('user_id', $teacher->user_id)
            ->where('subject_id', $subject->subject_id)
            ->where('role_in_subject', 'teacher')
            ->exists();

        if (!$hasAccess) {
            abort(403, 'You do not have access to this subject.');
        }

        // Get analytics data for this subject
        $analytics = [
            'student_engagement' => [
                'total_students' => DB::table('user_subjects')
                    ->where('subject_id', $subject->subject_id)
                    ->where('role_in_subject', 'student')
                    ->count(),
                'active_students' => rand(8, 12), // TODO: Calculate real active students
                'engagement_rate' => rand(75, 95), // TODO: Calculate real engagement
            ],
            'content_performance' => [
                'total_notes' => DB::table('note_subjects')
                    ->join('notes', 'note_subjects.note_id', '=', 'notes.note_id')
                    ->where('note_subjects.subject_id', $subject->subject_id)
                    ->where('notes.user_id', $teacher->user_id)
                    ->count(),
                'avg_note_views' => rand(15, 45), // TODO: Calculate real views
                'most_viewed_note' => 'Introduction to Programming', // TODO: Get real data
            ],
            'question_analytics' => [
                'total_questions' => DB::table('note_subjects')
                    ->join('notes', 'note_subjects.note_id', '=', 'notes.note_id')
                    ->join('questions', 'notes.note_id', '=', 'questions.note_id')
                    ->where('note_subjects.subject_id', $subject->subject_id)
                    ->where('notes.user_id', $teacher->user_id)
                    ->count(),
                'avg_score' => rand(70, 90), // TODO: Calculate real scores
                'completion_rate' => rand(80, 95), // TODO: Calculate real completion
            ]
        ];

        return view('teacher.subjects.analytics', compact('subject', 'analytics'));
    }
}
