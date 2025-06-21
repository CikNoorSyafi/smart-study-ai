<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with(['profile', 'subjects']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subjects = Subject::all();
        return view('admin.users.create', compact('subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,teacher,student,parent',
            'is_active' => 'boolean',
            'subjects' => 'array',
            'subjects.*' => 'exists:subjects,subject_id',
        ]);

        $user = User::create([
            'user_id' => (string) Str::uuid(),
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => $request->boolean('is_active', true),
            'email_verified_at' => now(),
        ]);

        // Attach subjects if provided
        if (!empty($validated['subjects'])) {
            foreach ($validated['subjects'] as $subjectId) {
                $user->subjects()->attach($subjectId, [
                    'role_in_subject' => $validated['role'] === 'teacher' ? 'teacher' : 'student',
                    'level' => 'beginner',
                ]);
            }
        }

        return redirect()->route('admin.users-crud.index')
                        ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['profile', 'subjects', 'notes.questions', 'feedback']);

        $stats = [
            'notes_count' => $user->notes()->count(),
            'questions_count' => $user->questions()->count(),
            'feedback_count' => $user->feedback()->count(),
            'subjects_count' => $user->subjects()->count(),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $subjects = Subject::all();
        $userSubjects = $user->subjects->pluck('subject_id')->toArray();

        return view('admin.users.edit', compact('user', 'subjects', 'userSubjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,teacher,student,parent',
            'is_active' => 'boolean',
            'subjects' => 'array',
            'subjects.*' => 'exists:subjects,subject_id',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'is_active' => $request->boolean('is_active'),
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        // Sync subjects
        if (isset($validated['subjects'])) {
            $syncData = [];
            foreach ($validated['subjects'] as $subjectId) {
                $syncData[$subjectId] = [
                    'role_in_subject' => $validated['role'] === 'teacher' ? 'teacher' : 'student',
                    'level' => 'beginner',
                ];
            }
            $user->subjects()->sync($syncData);
        } else {
            $user->subjects()->detach();
        }

        return redirect()->route('admin.users-crud.index')
                        ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deletion of the last admin
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return redirect()->route('admin.users-crud.index')
                            ->with('error', 'Cannot delete the last admin user.');
        }

        $user->delete();

        return redirect()->route('admin.users-crud.index')
                        ->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "User {$status} successfully.");
    }
}
