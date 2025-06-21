@extends('teacher.layout')

@section('title', $subject->name . ' - Subject Details')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <a href="{{ route('teacher.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-home"></i>
                        <span class="sr-only">Dashboard</span>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('teacher.subjects') }}" class="text-gray-500 hover:text-gray-700">Subjects</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-gray-900">{{ $subject->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-3xl font-bold text-gray-900 mt-2">{{ $subject->name }}</h1>
        <p class="mt-1 text-sm text-gray-600">Comprehensive overview and management for this subject.</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('teacher.notes.create') }}?subject={{ $subject->subject_id }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
            <i class="fas fa-plus mr-2"></i>Create Content
        </a>
        <a href="{{ route('teacher.subjects.students', $subject) }}" class="bg-secondary text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
            <i class="fas fa-users mr-2"></i>Manage Students
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <!-- Subject Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-users text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Students Enrolled</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total_students'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
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
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Published Notes</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['published_notes'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

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
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-bolt text-primary mr-2"></i>
                        Quick Actions for {{ $subject->name }}
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('teacher.notes.create') }}?subject={{ $subject->subject_id }}" class="block p-4 border-2 border-gray-200 rounded-lg hover:border-primary hover:shadow-md transition-all">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-plus text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <h4 class="font-medium text-gray-900">Create Note</h4>
                                    <p class="text-sm text-gray-500">Add study material</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('teacher.questions.create') }}?subject={{ $subject->subject_id }}" class="block p-4 border-2 border-gray-200 rounded-lg hover:border-secondary hover:shadow-md transition-all">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-question-circle text-green-600"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <h4 class="font-medium text-gray-900">Create Questions</h4>
                                    <p class="text-sm text-gray-500">Generate quiz questions</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('teacher.subjects.students', $subject) }}" class="block p-4 border-2 border-gray-200 rounded-lg hover:border-accent hover:shadow-md transition-all">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-users text-yellow-600"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <h4 class="font-medium text-gray-900">Manage Students</h4>
                                    <p class="text-sm text-gray-500">View and assign students</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('teacher.subjects.analytics', $subject) }}" class="block p-4 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:shadow-md transition-all">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-chart-bar text-purple-600"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <h4 class="font-medium text-gray-900">View Analytics</h4>
                                    <p class="text-sm text-gray-500">Performance metrics</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Notes -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Recent Study Notes</h3>
                        <a href="{{ route('teacher.subjects.content', $subject) }}" class="text-primary hover:text-blue-600 text-sm font-medium">
                            View all →
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if($recentNotes->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentNotes as $note)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $note->title }}</h4>
                                    <p class="text-sm text-gray-500 mt-1">{{ Str::limit($note->content, 80) }}</p>
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
                                    <a href="{{ route('teacher.notes.edit', $note) }}" class="text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-file-alt text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">No study notes created for this subject yet.</p>
                            <a href="{{ route('teacher.notes.create') }}?subject={{ $subject->subject_id }}" class="mt-2 bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors inline-block">
                                <i class="fas fa-plus mr-2"></i>Create First Note
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-8">
            <!-- Recent Students -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Recent Students</h3>
                        <a href="{{ route('teacher.subjects.students', $subject) }}" class="text-primary hover:text-blue-600 text-sm font-medium">
                            View all →
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if($recentStudents->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentStudents as $student)
                            <div class="flex items-center">
                                <img class="h-10 w-10 rounded-full" src="{{ $student->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&background=3B82F6&color=fff' }}" alt="{{ $student->name }}">
                                <div class="ml-4 flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $student->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $student->email }}</p>
                                </div>
                                <div class="ml-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users text-2xl text-gray-300 mb-2"></i>
                            <p class="text-gray-500 text-sm">No students enrolled yet.</p>
                        </div>
                    @endif
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

            <!-- Subject Navigation -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-medium text-blue-900 mb-4">
                    <i class="fas fa-compass text-blue-600 mr-2"></i>
                    Subject Navigation
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('teacher.subjects.content', $subject) }}" class="block p-3 bg-white border border-blue-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt text-blue-600 mr-3"></i>
                            <span class="text-sm font-medium text-blue-800">All Content</span>
                        </div>
                    </a>
                    <a href="{{ route('teacher.subjects.students', $subject) }}" class="block p-3 bg-white border border-blue-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-users text-blue-600 mr-3"></i>
                            <span class="text-sm font-medium text-blue-800">All Students</span>
                        </div>
                    </a>
                    <a href="{{ route('teacher.subjects.analytics', $subject) }}" class="block p-3 bg-white border border-blue-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-chart-line text-blue-600 mr-3"></i>
                            <span class="text-sm font-medium text-blue-800">Analytics</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
