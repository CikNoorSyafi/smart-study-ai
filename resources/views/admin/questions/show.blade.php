@extends('layouts.admin')

@section('title', 'Question Details')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <h1 class="text-3xl font-bold text-gray-900">Question Details</h1>
                        @if($question->question_type)
                        <span class="px-3 py-1 
                            @if($question->question_type === 'multiple_choice') bg-blue-100 text-blue-700
                            @elseif($question->question_type === 'true_false') bg-green-100 text-green-700
                            @elseif($question->question_type === 'short_answer') bg-yellow-100 text-yellow-700
                            @elseif($question->question_type === 'essay') bg-purple-100 text-purple-700
                            @else bg-gray-100 text-gray-700
                            @endif
                            text-sm rounded-full font-medium">{{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</span>
                        @endif
                        @if($question->difficulty_level)
                        <span class="px-3 py-1 
                            @if($question->difficulty_level === 'easy') bg-green-100 text-green-700
                            @elseif($question->difficulty_level === 'medium') bg-yellow-100 text-yellow-700
                            @elseif($question->difficulty_level === 'hard') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700
                            @endif
                            text-sm rounded-full font-medium">{{ ucfirst($question->difficulty_level) }}</span>
                        @endif
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.questions.edit', $question) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit Question
                    </a>
                    <a href="{{ route('admin.questions.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Questions
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Question Information -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Question</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Question Text</label>
                                <div class="bg-gray-50 rounded-lg p-4 border">
                                    <p class="text-gray-900 whitespace-pre-wrap">{{ $question->question_text }}</p>
                                </div>
                            </div>
                            
                            @if($question->explanation)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Explanation</label>
                                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                    <p class="text-blue-900 whitespace-pre-wrap">{{ $question->explanation }}</p>
                                </div>
                            </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Question Type</label>
                                    <p class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Difficulty Level</label>
                                    <p class="text-gray-900">{{ $question->difficulty_level ? ucfirst($question->difficulty_level) : 'Not specified' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                                    <p class="text-gray-900">{{ $question->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>

                            @if($question->note)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Associated Note</label>
                                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-green-900 font-medium">{{ $question->note->title }}</h4>
                                            <p class="text-green-700 text-sm">{{ Str::limit($question->note->content, 100) }}</p>
                                        </div>
                                        <a href="{{ route('admin.notes-crud.show', $question->note) }}" class="text-green-600 hover:text-green-800">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($question->user)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Created By</label>
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-medium">{{ strtoupper(substr($question->user->name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-gray-900 font-medium">{{ $question->user->name }}</p>
                                        <p class="text-gray-500 text-sm">{{ $question->user->email }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Answer Options -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Answer Options</h3>
                            <a href="{{ route('admin.answers.create', ['question_id' => $question->question_id]) }}" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition-colors">
                                <i class="fas fa-plus mr-1"></i>Add Answer
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($question->answers && $question->answers->count() > 0)
                        <div class="space-y-3">
                            @foreach($question->answers as $answer)
                            <div class="border rounded-lg p-4 {{ $answer->is_correct ? 'border-green-300 bg-green-50' : 'border-gray-200' }}">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            @if($answer->is_correct)
                                            <i class="fas fa-check-circle text-green-600"></i>
                                            <span class="text-green-700 font-medium">Correct Answer</span>
                                            @else
                                            <i class="fas fa-circle text-gray-400"></i>
                                            <span class="text-gray-700">Option</span>
                                            @endif
                                        </div>
                                        <p class="text-gray-900">{{ $answer->answer_text }}</p>
                                        @if($answer->explanation)
                                        <p class="text-gray-600 text-sm mt-2">{{ $answer->explanation }}</p>
                                        @endif
                                    </div>
                                    <div class="ml-4 flex space-x-2">
                                        <a href="{{ route('admin.answers.edit', $answer) }}" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.answers.destroy', $answer) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this answer?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8">
                            <i class="fas fa-list-ul text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-500 mb-4">No answer options added yet.</p>
                            <a href="{{ route('admin.answers.create', ['question_id' => $question->question_id]) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i>Add First Answer
                            </a>
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
                        <h3 class="text-lg font-semibold text-gray-900">Statistics</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Total Answers</span>
                                <span class="text-lg font-semibold text-blue-600">{{ $stats['total_answers'] ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Correct Answers</span>
                                <span class="text-lg font-semibold text-green-600">{{ $stats['correct_answers'] ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Feedback Count</span>
                                <span class="text-lg font-semibold text-purple-600">{{ $stats['feedback_count'] ?? 0 }}</span>
                            </div>
                            @if(isset($stats['avg_rating']) && $stats['avg_rating'])
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Average Rating</span>
                                <span class="text-lg font-semibold text-yellow-600">{{ number_format($stats['avg_rating'], 1) }}/5</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('admin.questions.edit', $question) }}" class="w-full bg-blue-100 text-blue-700 text-center py-2 rounded-md hover:bg-blue-200 transition-colors block">
                            <i class="fas fa-edit mr-2"></i>Edit Question
                        </a>
                        <a href="{{ route('admin.answers.create', ['question_id' => $question->question_id]) }}" class="w-full bg-green-100 text-green-700 text-center py-2 rounded-md hover:bg-green-200 transition-colors block">
                            <i class="fas fa-plus mr-2"></i>Add Answer
                        </a>
                        @if($question->note)
                        <a href="{{ route('admin.notes-crud.show', $question->note) }}" class="w-full bg-purple-100 text-purple-700 text-center py-2 rounded-md hover:bg-purple-200 transition-colors block">
                            <i class="fas fa-sticky-note mr-2"></i>View Note
                        </a>
                        @endif
                        @if(!$question->answers()->exists())
                        <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="w-full"
                              onsubmit="return confirm('Are you sure you want to delete this question? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-100 text-red-700 text-center py-2 rounded-md hover:bg-red-200 transition-colors">
                                <i class="fas fa-trash mr-2"></i>Delete Question
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
