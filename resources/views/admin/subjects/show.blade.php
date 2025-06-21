@extends('layouts.admin')

@section('title', 'Subject Details - ' . $subject->name)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $subject->name }}</h1>
                        @if($subject->subject_code)
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full font-mono">{{ $subject->subject_code }}</span>
                        @endif
                    </div>
                    <div class="flex items-center space-x-3">
                        @if($subject->form_level)
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm rounded-full font-medium">{{ $subject->form_level }}</span>
                        @endif
                        @if($subject->category)
                        <span class="px-3 py-1 
                            @if($subject->category === 'Core') bg-red-100 text-red-700
                            @elseif($subject->category === 'Science') bg-green-100 text-green-700
                            @elseif($subject->category === 'Arts') bg-yellow-100 text-yellow-700
                            @elseif($subject->category === 'Technical') bg-purple-100 text-purple-700
                            @else bg-gray-100 text-gray-700
                            @endif
                            text-sm rounded-full font-medium">{{ $subject->category }}</span>
                        @endif
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.subjects.edit', $subject) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit Subject
                    </a>
                    <a href="{{ route('admin.subjects.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Subjects
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Subject Information -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Subject Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <p class="text-gray-900">{{ $subject->description ?: 'No description available.' }}</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Form Level</label>
                                    <p class="text-gray-900">{{ $subject->form_level ?: 'Not specified' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                    <p class="text-gray-900">{{ $subject->category ?: 'Not specified' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject Code</label>
                                    <p class="text-gray-900 font-mono">{{ $subject->subject_code ?: 'Not specified' }}</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                                <p class="text-gray-900">{{ $subject->created_at->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Associated Notes -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Associated Notes</h3>
                    </div>
                    <div class="p-6">
                        @if($subject->notes && $subject->notes->count() > 0)
                        <div class="space-y-4">
                            @foreach($subject->notes as $note)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-medium text-gray-900 mb-2">{{ $note->title }}</h4>
                                        <p class="text-gray-600 text-sm mb-2">{{ Str::limit($note->content, 150) }}</p>
                                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                                            <span><i class="fas fa-user mr-1"></i>{{ $note->user->name ?? 'Unknown' }}</span>
                                            <span><i class="fas fa-calendar mr-1"></i>{{ $note->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <a href="{{ route('admin.notes-crud.show', $note) }}" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8">
                            <i class="fas fa-sticky-note text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-500">No notes associated with this subject yet.</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Associated Users -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Enrolled Users</h3>
                    </div>
                    <div class="p-6">
                        @if($subject->users && $subject->users->count() > 0)
                        <div class="space-y-4">
                            @foreach($subject->users as $user)
                            <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-medium">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $user->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($user->pivot->role_in_subject === 'teacher') bg-green-100 text-green-800
                                        @elseif($user->pivot->role_in_subject === 'student') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($user->pivot->role_in_subject ?? 'User') }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8">
                            <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-500">No users enrolled in this subject yet.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Quick Statistics</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Total Notes</span>
                                <span class="text-lg font-semibold text-blue-600">{{ $subject->notes->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Enrolled Users</span>
                                <span class="text-lg font-semibold text-green-600">{{ $subject->users->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Teachers</span>
                                <span class="text-lg font-semibold text-purple-600">{{ $subject->teachers()->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Students</span>
                                <span class="text-lg font-semibold text-yellow-600">{{ $subject->students()->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('admin.subjects.edit', $subject) }}" class="w-full bg-blue-100 text-blue-700 text-center py-2 rounded-md hover:bg-blue-200 transition-colors block">
                            <i class="fas fa-edit mr-2"></i>Edit Subject
                        </a>
                        <a href="{{ route('admin.notes-crud.index', ['subject' => $subject->subject_id]) }}" class="w-full bg-green-100 text-green-700 text-center py-2 rounded-md hover:bg-green-200 transition-colors block">
                            <i class="fas fa-sticky-note mr-2"></i>View Notes
                        </a>
                        <a href="{{ route('admin.users-crud.index', ['subject' => $subject->subject_id]) }}" class="w-full bg-purple-100 text-purple-700 text-center py-2 rounded-md hover:bg-purple-200 transition-colors block">
                            <i class="fas fa-users mr-2"></i>Manage Users
                        </a>
                        @if(!$subject->users()->exists() && !$subject->notes()->exists())
                        <form action="{{ route('admin.subjects.destroy', $subject) }}" method="POST" class="w-full"
                              onsubmit="return confirm('Are you sure you want to delete this subject? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-100 text-red-700 text-center py-2 rounded-md hover:bg-red-200 transition-colors">
                                <i class="fas fa-trash mr-2"></i>Delete Subject
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                <!-- Malaysian Education Context -->
                @if($subject->form_level && $subject->category)
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Malaysian Education Context</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Education Level:</span>
                                <p class="text-sm text-gray-900">{{ $subject->form_level }} - Malaysian Secondary Education</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">Subject Stream:</span>
                                <p class="text-sm text-gray-900">
                                    @if($subject->category === 'Core')
                                    Core Subject (Compulsory for all students)
                                    @elseif($subject->category === 'Science')
                                    Science Stream (For science-oriented students)
                                    @elseif($subject->category === 'Arts')
                                    Arts Stream (For arts and humanities students)
                                    @elseif($subject->category === 'Technical')
                                    Technical/Vocational (Skill-based learning)
                                    @else
                                    {{ $subject->category }}
                                    @endif
                                </p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">SPM Preparation:</span>
                                <p class="text-sm text-gray-900">This subject is part of the SPM (Sijil Pelajaran Malaysia) curriculum</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
