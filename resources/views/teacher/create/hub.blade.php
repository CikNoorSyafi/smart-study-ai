@extends('teacher.layout')

@section('title', 'Create Content')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Create Content</h1>
        <p class="mt-1 text-sm text-gray-600">Choose what type of content you'd like to create for your students.</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('teacher.dashboard') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <!-- Content Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Notes</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $contentStats['total_notes'] }}</dd>
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
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Published</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $contentStats['published_notes'] }}</dd>
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
                            <i class="fas fa-edit text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Drafts</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $contentStats['draft_notes'] }}</dd>
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
                            <dd class="text-lg font-medium text-gray-900">{{ $contentStats['total_questions'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Creation Options -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Primary Content Types -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-plus-circle text-primary mr-2"></i>
                    Create New Content
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <!-- Study Note -->
                <a href="{{ route('teacher.notes.create') }}" class="block p-6 border-2 border-gray-200 rounded-lg hover:border-primary hover:shadow-md transition-all">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-alt text-2xl text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Study Note</h4>
                            <p class="text-sm text-gray-600 mb-3">Create comprehensive study materials with rich content, assign to subjects, and publish for students.</p>
                            <div class="flex items-center text-sm text-blue-600">
                                <span class="font-medium">Create Note</span>
                                <i class="fas fa-arrow-right ml-2"></i>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Questions -->
                <a href="{{ route('teacher.questions.create') }}" class="block p-6 border-2 border-gray-200 rounded-lg hover:border-secondary hover:shadow-md transition-all">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-question-circle text-2xl text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Questions & Quiz</h4>
                            <p class="text-sm text-gray-600 mb-3">Generate multiple choice questions from your notes or create custom quizzes for student assessment.</p>
                            <div class="flex items-center text-sm text-green-600">
                                <span class="font-medium">Create Questions</span>
                                <i class="fas fa-arrow-right ml-2"></i>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Future: Assignment -->
                <div class="block p-6 border-2 border-gray-100 rounded-lg bg-gray-50 opacity-75">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-tasks text-2xl text-gray-400"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="text-lg font-medium text-gray-500 mb-2">Assignment</h4>
                            <p class="text-sm text-gray-500 mb-3">Create assignments and homework tasks for students. (Coming Soon)</p>
                            <div class="flex items-center text-sm text-gray-400">
                                <span class="font-medium">Coming Soon</span>
                                <i class="fas fa-clock ml-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Templates -->
        <div class="space-y-6">
            <!-- Quick Templates -->
            @if($recentNotes->count() > 0)
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-copy text-accent mr-2"></i>
                        Use as Template
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach($recentNotes->take(3) as $note)
                        <a href="{{ route('teacher.notes.create') }}?template={{ $note->note_id }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $note->title }}</h4>
                                    <p class="text-sm text-gray-500">{{ Str::limit($note->content, 80) }}</p>
                                </div>
                                <div class="ml-4">
                                    <i class="fas fa-copy text-gray-400"></i>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Subject Context -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-book text-primary mr-2"></i>
                        Your Subjects
                    </h3>
                </div>
                <div class="p-6">
                    @if($teacherSubjects->count() > 0)
                        <div class="space-y-3">
                            @foreach($teacherSubjects as $subject)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $subject->name }}</h4>
                                    <p class="text-sm text-gray-500">
                                        @php
                                            $studentCount = DB::table('user_subjects')
                                                ->where('subject_id', $subject->subject_id)
                                                ->where('role_in_subject', 'student')
                                                ->count();
                                        @endphp
                                        {{ $studentCount }} students enrolled
                                    </p>
                                </div>
                                <a href="{{ route('teacher.notes.create') }}?subject={{ $subject->subject_id }}" class="text-primary hover:text-blue-600">
                                    <i class="fas fa-plus-circle"></i>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">
                            <i class="fas fa-info-circle mr-2"></i>
                            No subjects assigned yet. Contact your administrator.
                        </p>
                    @endif
                </div>
            </div>

            <!-- Quick Tips -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-medium text-blue-900 mb-4">
                    <i class="fas fa-lightbulb text-blue-600 mr-2"></i>
                    Content Creation Tips
                </h3>
                <ul class="space-y-2 text-sm text-blue-800">
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-600 mr-2 mt-1"></i>
                        <span>Start with study notes to build your content library</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-600 mr-2 mt-1"></i>
                        <span>Generate questions from notes for quick assessments</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-600 mr-2 mt-1"></i>
                        <span>Use templates to speed up content creation</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-600 mr-2 mt-1"></i>
                        <span>Assign content to specific subjects for organization</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
