@extends('teacher.layout')

@section('title', 'Teacher Dashboard')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ $teacher->name }}!</h1>
        <p class="mt-1 text-sm text-gray-600">Here's what's happening with your classes today.</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('teacher.create.hub') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
            <i class="fas fa-plus mr-2"></i>Create Content
        </a>
        <a href="{{ route('teacher.students.hub') }}" class="bg-secondary text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
            <i class="fas fa-users mr-2"></i>Manage Students
        </a>
    </div>
</div>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Subjects -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-book text-white"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">My Subjects</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $stats['total_subjects'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <a href="{{ route('teacher.subjects') }}" class="font-medium text-blue-600 hover:text-blue-500">View all subjects</a>
            </div>
        </div>
    </div>

    <!-- Total Students -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-users text-white"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">My Students</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $stats['total_students'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <a href="{{ route('teacher.students') }}" class="font-medium text-green-600 hover:text-green-500">View all students</a>
            </div>
        </div>
    </div>

    <!-- Total Notes -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Study Notes</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $stats['total_notes'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <a href="{{ route('teacher.content') }}" class="font-medium text-yellow-600 hover:text-yellow-500">Manage content</a>
            </div>
        </div>
    </div>

    <!-- Total Questions -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-question-circle text-white"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Questions</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $stats['total_questions'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <a href="{{ route('teacher.analytics') }}" class="font-medium text-purple-600 hover:text-purple-500">View analytics</a>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column -->
    <div class="lg:col-span-2 space-y-8">
        <!-- My Subjects -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">My Subjects</h3>
            </div>
            <div class="p-6">
                @if($teacherSubjects->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($teacherSubjects->take(4) as $subject)
                        <a href="{{ route('teacher.subjects.detail', $subject) }}" class="block border border-gray-200 rounded-lg p-4 hover:shadow-md hover:border-primary transition-all cursor-pointer">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $subject->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $subject->users_count }} students</p>
                                    <p class="text-sm text-gray-500">{{ $subject->notes_count }} notes</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                    <div class="mt-2">
                                        <i class="fas fa-arrow-right text-gray-400 text-sm"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @if($teacherSubjects->count() > 4)
                    <div class="mt-4 text-center">
                        <a href="{{ route('teacher.subjects') }}" class="text-primary hover:text-blue-600 font-medium">
                            View all {{ $teacherSubjects->count() }} subjects →
                        </a>
                    </div>
                    @endif
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-book text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No subjects assigned yet.</p>
                        <p class="text-sm text-gray-400">Contact your administrator to get assigned to subjects.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Notes -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Recent Study Notes</h3>
            </div>
            <div class="p-6">
                @if($teacherNotes->count() > 0)
                    <div class="space-y-4">
                        @foreach($teacherNotes as $note)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $note->title }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($note->content, 100) }}</p>
                                <div class="flex items-center mt-2 space-x-4">
                                    <span class="text-xs text-gray-400">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $note->created_at->format('M j, Y') }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                        @if($note->status === 'published') bg-green-100 text-green-800 
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($note->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('teacher.content') }}" class="text-primary hover:text-blue-600 font-medium">
                            View all notes →
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-file-alt text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No study notes created yet.</p>
                        <a href="{{ route('teacher.notes.create') }}" class="mt-2 bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors inline-block">
                            <i class="fas fa-plus mr-2"></i>Create Your First Note
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="space-y-8">
        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
            </div>
            <div class="p-6 space-y-3">
                <a href="{{ route('teacher.notes.create') }}" class="w-full flex items-center px-4 py-3 text-left border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-plus-circle text-primary mr-3"></i>
                    <span class="font-medium">Create New Note</span>
                </a>
                <a href="{{ route('teacher.questions.create') }}" class="w-full flex items-center px-4 py-3 text-left border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-question-circle text-secondary mr-3"></i>
                    <span class="font-medium">Generate Questions</span>
                </a>
                <a href="{{ route('teacher.students') }}" class="w-full flex items-center px-4 py-3 text-left border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-users text-accent mr-3"></i>
                    <span class="font-medium">View Students</span>
                </a>
                <a href="{{ route('teacher.analytics') }}" class="w-full flex items-center px-4 py-3 text-left border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-chart-line text-purple-500 mr-3"></i>
                    <span class="font-medium">View Analytics</span>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($recentActivity as $activity)
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                <i class="{{ $activity['icon'] }} text-sm {{ $activity['color'] }}"></i>
                            </div>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm text-gray-900">{{ $activity['description'] }}</p>
                            <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Content Statistics -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Content Overview</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Published Notes</span>
                        <span class="font-medium text-green-600">{{ $stats['published_notes'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Draft Notes</span>
                        <span class="font-medium text-yellow-600">{{ $stats['draft_notes'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Questions</span>
                        <span class="font-medium text-purple-600">{{ $stats['total_questions'] }}</span>
                    </div>
                    <div class="pt-4 border-t border-gray-200">
                        <a href="{{ route('teacher.analytics') }}" class="text-primary hover:text-blue-600 text-sm font-medium">
                            View detailed analytics →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
