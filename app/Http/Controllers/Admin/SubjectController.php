<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Subject::withCount(['users', 'notes']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('form_level', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('subject_code', 'like', "%{$search}%");
            });
        }

        // Filter by form level if specified
        if ($request->filled('form_level')) {
            $query->where('form_level', $request->form_level);
        }

        // Filter by category if specified
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $subjects = $query->orderBy('form_level')->orderBy('category')->orderBy('name')->paginate(15);

        // Get all subjects for statistics (not paginated)
        $allSubjects = Subject::withCount(['users', 'notes'])->get();

        // Group subjects by form level and category for organized display
        $groupedSubjects = $subjects->groupBy(['form_level', 'category']);

        // Get filter options
        $formLevels = Subject::distinct()->pluck('form_level')->filter();
        $categories = Subject::distinct()->pluck('category')->filter();

        // Calculate statistics safely
        $statistics = [
            'total_subjects' => $allSubjects->count(),
            'with_notes' => $allSubjects->filter(function($subject) {
                return $subject->notes_count > 0;
            })->count(),
            'with_users' => $allSubjects->filter(function($subject) {
                return $subject->users_count > 0;
            })->count(),
            'total_notes' => $allSubjects->sum('notes_count'),
            'form_stats' => [],
            'category_stats' => []
        ];

        // Form level statistics - safely handle collections
        $formLevelsArray = $formLevels->toArray();
        foreach($formLevelsArray as $level) {
            $levelSubjects = $allSubjects->filter(function($subject) use ($level) {
                return $subject->form_level === $level;
            });
            $statistics['form_stats'][$level] = [
                'count' => $levelSubjects->count(),
                'notes' => $levelSubjects->sum('notes_count')
            ];
        }

        // Category statistics - safely handle collections
        $categoriesArray = $categories->toArray();
        foreach($categoriesArray as $category) {
            $categorySubjects = $allSubjects->filter(function($subject) use ($category) {
                return $subject->category === $category;
            });
            $statistics['category_stats'][$category] = [
                'count' => $categorySubjects->count(),
                'notes' => $categorySubjects->sum('notes_count')
            ];
        }

        return view('admin.subjects.index', compact('subjects', 'groupedSubjects', 'formLevels', 'categories', 'statistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.subjects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subjects',
            'description' => 'nullable|string|max:1000',
            'form_level' => 'nullable|string|in:Form 4,Form 5',
            'category' => 'nullable|string|in:Core,Science,Arts,Technical',
            'subject_code' => 'nullable|string|max:10',
        ]);

        $subject = Subject::create([
            'subject_id' => (string) Str::uuid(),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'form_level' => $validated['form_level'],
            'category' => $validated['category'],
            'subject_code' => $validated['subject_code'],
        ]);

        return redirect()->route('admin.subjects.show', $subject)
                        ->with('success', 'Subject created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        $subject->load(['users.profile', 'notes.user']);

        $stats = [
            'total_users' => $subject->users()->count(),
            'teachers' => $subject->teachers()->count(),
            'students' => $subject->students()->count(),
            'total_notes' => $subject->notes()->count(),
            'published_notes' => $subject->notes()->where('status', 'published')->count(),
        ];

        return view('admin.subjects.show', compact('subject', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name,' . $subject->subject_id . ',subject_id',
            'description' => 'nullable|string|max:1000',
            'form_level' => 'nullable|string|in:Form 4,Form 5',
            'category' => 'nullable|string|in:Core,Science,Arts,Technical',
            'subject_code' => 'nullable|string|max:10',
        ]);

        $subject->update($validated);

        return redirect()->route('admin.subjects.show', $subject)
                        ->with('success', 'Subject updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        // Check if subject has associated data
        $hasUsers = $subject->users()->exists();
        $hasNotes = $subject->notes()->exists();

        if ($hasUsers || $hasNotes) {
            return redirect()->route('admin.subjects.index')
                            ->with('error', 'Cannot delete subject with associated users or notes.');
        }

        $subject->delete();

        return redirect()->route('admin.subjects.index')
                        ->with('success', 'Subject deleted successfully.');
    }

    /**
     * Get subject statistics for API.
     */
    public function statistics(Subject $subject)
    {
        return response()->json([
            'users_count' => $subject->users()->count(),
            'notes_count' => $subject->notes()->count(),
            'teachers_count' => $subject->teachers()->count(),
            'students_count' => $subject->students()->count(),
        ]);
    }
}
