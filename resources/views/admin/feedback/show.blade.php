@extends('layouts.admin')

@section('title', 'Feedback Details')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Feedback Details</h1>
                    <p class="mt-1 text-sm text-gray-600">View detailed feedback information</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.feedback.edit', $feedback) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit Feedback
                    </a>
                    <a href="{{ route('admin.feedback.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Feedback
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Feedback Details -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <!-- Feedback Header -->
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">Feedback #{{ substr($feedback->feedback_id, 0, 8) }}</h2>
                                <p class="text-gray-600">Submitted {{ $feedback->created_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                            <div class="text-right">
                                <!-- Rating Display -->
                                <div class="flex items-center space-x-1 mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $feedback->rating)
                                            <i class="fas fa-star text-yellow-500 text-lg"></i>
                                        @else
                                            <i class="far fa-star text-gray-300 text-lg"></i>
                                        @endif
                                    @endfor
                                    <span class="ml-2 text-lg font-semibold text-gray-900">{{ $feedback->rating }}/5</span>
                                </div>
                                <!-- Feedback Type Badge -->
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                    @if($feedback->isPositive()) bg-green-100 text-green-800
                                    @elseif($feedback->isNegative()) bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($feedback->type) }}
                                </span>
                            </div>
                        </div>

                        <!-- User Information -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">User Information</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                        <span class="text-white text-lg font-bold">{{ substr($feedback->user->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-lg font-semibold text-gray-900">{{ $feedback->user->name }}</p>
                                        <p class="text-gray-600">{{ $feedback->user->email }}</p>
                                        <p class="text-sm text-gray-500">{{ ucfirst($feedback->user->role) }}</p>
                                    </div>
                                </div>
                                @if($feedback->user->profile)
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        @if($feedback->user->profile->phone_number)
                                        <div>
                                            <span class="text-gray-500">Phone:</span>
                                            <span class="text-gray-900">{{ $feedback->user->profile->phone_number }}</span>
                                        </div>
                                        @endif
                                        @if($feedback->user->profile->preferred_language)
                                        <div>
                                            <span class="text-gray-500">Language:</span>
                                            <span class="text-gray-900">{{ strtoupper($feedback->user->profile->preferred_language) }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Related Question -->
                        @if($feedback->question)
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Related Question</h3>
                            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                <div class="flex justify-between items-start mb-3">
                                    <h4 class="font-medium text-blue-900">Question</h4>
                                    @if($feedback->question->note)
                                    <span class="text-sm text-blue-600 bg-blue-100 px-2 py-1 rounded">
                                        {{ $feedback->question->note->title }}
                                    </span>
                                    @endif
                                </div>
                                <p class="text-blue-800">{{ $feedback->question->question_text }}</p>
                                @if($feedback->question->difficulty)
                                <div class="mt-2">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ ucfirst($feedback->question->difficulty) }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Related Answer -->
                        @if($feedback->answer)
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Related Answer</h3>
                            <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                <div class="flex justify-between items-start mb-3">
                                    <h4 class="font-medium text-green-900">Answer</h4>
                                    @if($feedback->answer->is_correct)
                                    <span class="text-sm text-green-600 bg-green-100 px-2 py-1 rounded flex items-center">
                                        <i class="fas fa-check mr-1"></i>Correct
                                    </span>
                                    @else
                                    <span class="text-sm text-red-600 bg-red-100 px-2 py-1 rounded flex items-center">
                                        <i class="fas fa-times mr-1"></i>Incorrect
                                    </span>
                                    @endif
                                </div>
                                <p class="text-green-800">{{ $feedback->answer->answer_text }}</p>
                                @if($feedback->answer->question && $feedback->answer->question->note)
                                <div class="mt-2">
                                    <span class="text-sm text-green-600">
                                        From: {{ $feedback->answer->question->note->title }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Comments -->
                        @if($feedback->comments)
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Comments</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-800 leading-relaxed">{{ $feedback->comments }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('admin.feedback.edit', $feedback) }}" 
                               class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                                <i class="fas fa-edit mr-2"></i>Edit Feedback
                            </a>
                            
                            <a href="{{ route('admin.users-crud.show', $feedback->user) }}" 
                               class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center">
                                <i class="fas fa-user mr-2"></i>View User
                            </a>

                            @if($feedback->question)
                            <a href="#" 
                               class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors flex items-center">
                                <i class="fas fa-question-circle mr-2"></i>View Question
                            </a>
                            @endif

                            <form action="{{ route('admin.feedback.destroy', $feedback) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this feedback?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center">
                                    <i class="fas fa-trash mr-2"></i>Delete Feedback
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Feedback Statistics -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Feedback Details</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Feedback ID:</span>
                                <span class="text-gray-900 font-mono">{{ substr($feedback->feedback_id, 0, 8) }}...</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Rating:</span>
                                <span class="text-gray-900 font-medium">{{ $feedback->rating }}/5 stars</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Type:</span>
                                <span class="text-gray-900 font-medium">{{ ucfirst($feedback->type) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Submitted:</span>
                                <span class="text-gray-900">{{ $feedback->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Time:</span>
                                <span class="text-gray-900">{{ $feedback->created_at->format('g:i A') }}</span>
                            </div>
                            @if($feedback->comments)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Comment Length:</span>
                                <span class="text-gray-900">{{ strlen($feedback->comments) }} chars</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
