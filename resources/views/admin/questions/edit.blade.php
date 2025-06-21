@extends('layouts.admin')

@section('title', 'Edit Question')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Question</h1>
                    <p class="mt-1 text-sm text-gray-600">Update question information and settings</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.questions.show', $question) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i>View Question
                    </a>
                    <a href="{{ route('admin.questions.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Questions
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Edit Form -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Question Information</h3>
                <p class="text-sm text-gray-600">Update the question details below</p>
            </div>
            
            <form action="{{ route('admin.questions.update', $question) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Question Text -->
                    <div>
                        <label for="question_text" class="block text-sm font-medium text-gray-700 mb-2">
                            Question Text <span class="text-red-500">*</span>
                        </label>
                        <textarea name="question_text" 
                                  id="question_text" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('question_text') border-red-500 @enderror"
                                  placeholder="Enter the question text..."
                                  required>{{ old('question_text', $question->question_text) }}</textarea>
                        @error('question_text')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Question Type -->
                    <div>
                        <label for="question_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Question Type <span class="text-red-500">*</span>
                        </label>
                        <select name="question_type" 
                                id="question_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('question_type') border-red-500 @enderror"
                                required>
                            <option value="">Select Question Type</option>
                            <option value="multiple_choice" {{ old('question_type', $question->question_type) === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                            <option value="true_false" {{ old('question_type', $question->question_type) === 'true_false' ? 'selected' : '' }}>True/False</option>
                            <option value="short_answer" {{ old('question_type', $question->question_type) === 'short_answer' ? 'selected' : '' }}>Short Answer</option>
                            <option value="essay" {{ old('question_type', $question->question_type) === 'essay' ? 'selected' : '' }}>Essay</option>
                        </select>
                        @error('question_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Difficulty Level -->
                    <div>
                        <label for="difficulty_level" class="block text-sm font-medium text-gray-700 mb-2">
                            Difficulty Level
                        </label>
                        <select name="difficulty_level" 
                                id="difficulty_level"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('difficulty_level') border-red-500 @enderror">
                            <option value="">Select Difficulty</option>
                            <option value="easy" {{ old('difficulty_level', $question->difficulty_level) === 'easy' ? 'selected' : '' }}>Easy</option>
                            <option value="medium" {{ old('difficulty_level', $question->difficulty_level) === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="hard" {{ old('difficulty_level', $question->difficulty_level) === 'hard' ? 'selected' : '' }}>Hard</option>
                        </select>
                        @error('difficulty_level')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Associated Note -->
                    <div>
                        <label for="note_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Associated Note
                        </label>
                        <select name="note_id" 
                                id="note_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('note_id') border-red-500 @enderror">
                            <option value="">Select Note (Optional)</option>
                            @foreach($notes as $note)
                            <option value="{{ $note->note_id }}" {{ old('note_id', $question->note_id) === $note->note_id ? 'selected' : '' }}>
                                {{ $note->title }}
                            </option>
                            @endforeach
                        </select>
                        @error('note_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Link this question to a specific note or study material</p>
                    </div>

                    <!-- Created By -->
                    <div>
                        <label for="created_by" class="block text-sm font-medium text-gray-700 mb-2">
                            Created By
                        </label>
                        <select name="created_by" 
                                id="created_by"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('created_by') border-red-500 @enderror">
                            <option value="">Select Creator (Optional)</option>
                            @foreach($users as $user)
                            <option value="{{ $user->user_id }}" {{ old('created_by', $question->created_by) === $user->user_id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                            @endforeach
                        </select>
                        @error('created_by')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Assign the question to a specific user</p>
                    </div>

                    <!-- Explanation (Optional) -->
                    <div>
                        <label for="explanation" class="block text-sm font-medium text-gray-700 mb-2">
                            Explanation
                        </label>
                        <textarea name="explanation" 
                                  id="explanation" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('explanation') border-red-500 @enderror"
                                  placeholder="Optional explanation or additional context for the question...">{{ old('explanation', $question->explanation) }}</textarea>
                        @error('explanation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Provide additional context or explanation for the question</p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200 mt-6">
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.questions.show', $question) }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                            Cancel
                        </a>
                    </div>
                    <div class="flex space-x-3">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>Update Question
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Current Question Info -->
        <div class="mt-8 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Current Question Information</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Current Details</h4>
                        <div class="space-y-2 text-sm">
                            <div><span class="font-medium">Type:</span> {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</div>
                            <div><span class="font-medium">Difficulty:</span> {{ $question->difficulty_level ? ucfirst($question->difficulty_level) : 'Not specified' }}</div>
                            <div><span class="font-medium">Associated Note:</span> {{ $question->note ? $question->note->title : 'None' }}</div>
                            <div><span class="font-medium">Created By:</span> {{ $question->user ? $question->user->name : 'Unknown' }}</div>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Answer Statistics</h4>
                        <div class="space-y-2 text-sm">
                            <div><span class="font-medium">Total Answers:</span> {{ $question->answers->count() }}</div>
                            <div><span class="font-medium">Correct Answers:</span> {{ $question->answers->where('is_correct', true)->count() }}</div>
                            <div><span class="font-medium">Created:</span> {{ $question->created_at->format('M d, Y') }}</div>
                            <div><span class="font-medium">Last Updated:</span> {{ $question->updated_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Answer Options Management -->
        @if($question->answers && $question->answers->count() > 0)
        <div class="mt-8 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Answer Options</h3>
                    <a href="{{ route('admin.answers.create', ['question_id' => $question->question_id]) }}" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition-colors">
                        <i class="fas fa-plus mr-1"></i>Add Answer
                    </a>
                </div>
            </div>
            <div class="p-6">
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
                            </div>
                            <div class="ml-4 flex space-x-2">
                                <a href="{{ route('admin.answers.edit', $answer) }}" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Question Guidelines -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="px-6 py-4">
                <h3 class="text-lg font-semibold text-blue-900 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>Question Guidelines
                </h3>
                <div class="text-sm text-blue-800 space-y-2">
                    <p><strong>Question Text:</strong> Write clear, concise questions that are easy to understand.</p>
                    <p><strong>Question Types:</strong></p>
                    <ul class="list-disc list-inside ml-4 space-y-1">
                        <li><strong>Multiple Choice:</strong> Best for testing specific knowledge with clear correct answers</li>
                        <li><strong>True/False:</strong> Good for testing understanding of concepts or facts</li>
                        <li><strong>Short Answer:</strong> Suitable for testing recall and brief explanations</li>
                        <li><strong>Essay:</strong> Best for testing critical thinking and detailed analysis</li>
                    </ul>
                    <p><strong>Difficulty Levels:</strong> Easy (basic recall), Medium (application), Hard (analysis/synthesis)</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-adjust textarea height
    document.getElementById('question_text').addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });

    document.getElementById('explanation').addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
</script>
@endsection
