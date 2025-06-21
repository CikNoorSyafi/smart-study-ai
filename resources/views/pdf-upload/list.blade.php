@extends('layouts.admin')

@section('title', 'Uploaded PDFs')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">PDF Library</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage your uploaded documents and generated questions</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('pdf-upload.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Upload New PDF
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($notes->count() > 0)
        <!-- PDF Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($notes as $note)
            <div class="bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                <div class="p-6">
                    <!-- PDF Header -->
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-pdf text-red-600 text-xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $note->title }}</h3>
                            <p class="text-sm text-gray-500 truncate">{{ $note->file_name }}</p>
                        </div>
                    </div>

                    <!-- PDF Details -->
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Subject:</span>
                            <span class="text-gray-900">{{ $note->subjects->first()->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Questions:</span>
                            <span class="text-gray-900 font-medium">{{ $note->questions->count() }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Uploaded:</span>
                            <span class="text-gray-900">{{ $note->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">File Size:</span>
                            <span class="text-gray-900">
                                @if($note->file_path && Storage::disk('public')->exists($note->file_path))
                                    {{ number_format(Storage::disk('public')->size($note->file_path) / 1024, 1) }}KB
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Question Types -->
                    @if($note->questions->count() > 0)
                    <div class="mb-4">
                        <p class="text-sm text-gray-500 mb-2">Question Types:</p>
                        <div class="flex flex-wrap gap-1">
                            @php
                                $questionTypes = $note->questions->groupBy(function($question) {
                                    // Determine question type based on answer count
                                    $answerCount = $question->answers->count();
                                    if ($answerCount == 2) return 'True/False';
                                    if ($answerCount > 2) return 'Multiple Choice';
                                    if ($answerCount == 1) return 'Short Answer';
                                    return 'Essay';
                                });
                            @endphp
                            @foreach($questionTypes as $type => $questions)
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $type }} ({{ $questions->count() }})
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Difficulty Distribution -->
                    @if($note->questions->count() > 0)
                    <div class="mb-4">
                        <p class="text-sm text-gray-500 mb-2">Difficulty:</p>
                        <div class="flex space-x-1">
                            @php
                                $difficulties = $note->questions->groupBy('difficulty');
                                $total = $note->questions->count();
                            @endphp
                            @foreach(['easy' => 'green', 'medium' => 'yellow', 'hard' => 'red'] as $difficulty => $color)
                                @if(isset($difficulties[$difficulty]))
                                <div class="flex-1 bg-{{ $color }}-200 rounded-full h-2" 
                                     title="{{ ucfirst($difficulty) }}: {{ $difficulties[$difficulty]->count() }}">
                                    <div class="bg-{{ $color }}-500 h-2 rounded-full" 
                                         style="width: {{ ($difficulties[$difficulty]->count() / $total) * 100 }}%"></div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <a href="{{ route('pdf-upload.result', $note->note_id) }}" 
                           class="flex-1 bg-blue-600 text-white text-center px-3 py-2 rounded-md text-sm hover:bg-blue-700 transition-colors">
                            <i class="fas fa-eye mr-1"></i>View
                        </a>
                        <a href="{{ route('pdf-upload.download', $note->note_id) }}" 
                           class="flex-1 bg-green-600 text-white text-center px-3 py-2 rounded-md text-sm hover:bg-green-700 transition-colors">
                            <i class="fas fa-download mr-1"></i>Download
                        </a>
                        <form action="{{ route('pdf-upload.delete', $note->note_id) }}" method="POST" class="flex-1"
                              onsubmit="return confirm('Are you sure you want to delete this PDF and all questions?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-600 text-white px-3 py-2 rounded-md text-sm hover:bg-red-700 transition-colors">
                                <i class="fas fa-trash mr-1"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($notes->hasPages())
        <div class="mt-8">
            {{ $notes->links() }}
        </div>
        @endif

        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="mx-auto w-24 h-24 text-gray-400 mb-4">
                <i class="fas fa-file-pdf text-6xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No PDFs uploaded yet</h3>
            <p class="text-gray-500 mb-6">Upload your first PDF to start generating questions automatically.</p>
            <a href="{{ route('pdf-upload.index') }}" 
               class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Upload Your First PDF
            </a>
        </div>
        @endif

        <!-- Statistics Summary -->
        @if($notes->count() > 0)
        <div class="mt-12 bg-white rounded-lg shadow">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Summary Statistics</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ $notes->total() }}</p>
                        <p class="text-sm text-gray-500">Total PDFs</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-green-600">{{ $notes->sum(function($note) { return $note->questions->count(); }) }}</p>
                        <p class="text-sm text-gray-500">Total Questions</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-purple-600">
                            {{ number_format($notes->sum(function($note) { 
                                return $note->file_path && Storage::disk('public')->exists($note->file_path) 
                                    ? Storage::disk('public')->size($note->file_path) 
                                    : 0; 
                            }) / 1024 / 1024, 1) }}MB
                        </p>
                        <p class="text-sm text-gray-500">Total Storage</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-yellow-600">
                            {{ $notes->avg(function($note) { return $note->questions->count(); }) ? number_format($notes->avg(function($note) { return $note->questions->count(); }), 1) : 0 }}
                        </p>
                        <p class="text-sm text-gray-500">Avg Questions/PDF</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
