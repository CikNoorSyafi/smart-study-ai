@extends('layouts.admin')

@section('title', 'Create Question')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create Question</h1>
                    <p class="mt-1 text-sm text-gray-600">Add a new question to the question bank</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.questions.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Questions
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Create Form -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Question Information</h3>
                <p class="text-sm text-gray-600">Fill in the details below to create a new question</p>
            </div>
            
            <form action="{{ route('admin.questions.store') }}" method="POST" class="p-6">
                @csrf
                
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
                                  required>{{ old('question_text') }}</textarea>
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
                            <option value="multiple_choice" {{ old('question_type') === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                            <option value="true_false" {{ old('question_type') === 'true_false' ? 'selected' : '' }}>True/False</option>
                            <option value="short_answer" {{ old('question_type') === 'short_answer' ? 'selected' : '' }}>Short Answer</option>
                            <option value="essay" {{ old('question_type') === 'essay' ? 'selected' : '' }}>Essay</option>
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
                            <option value="easy" {{ old('difficulty_level') === 'easy' ? 'selected' : '' }}>Easy</option>
                            <option value="medium" {{ old('difficulty_level') === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="hard" {{ old('difficulty_level') === 'hard' ? 'selected' : '' }}>Hard</option>
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
                            <option value="{{ $note->note_id }}" {{ old('note_id') === $note->note_id ? 'selected' : '' }}>
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
                            <option value="{{ $user->user_id }}" {{ old('created_by') === $user->user_id ? 'selected' : '' }}>
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
                                  placeholder="Optional explanation or additional context for the question...">{{ old('explanation') }}</textarea>
                        @error('explanation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Provide additional context or explanation for the question</p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200 mt-6">
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.questions.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                            Cancel
                        </a>
                    </div>
                    <div class="flex space-x-3">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>Create Question
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Question Types Guide -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="px-6 py-4">
                <h3 class="text-lg font-semibold text-blue-900 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>Question Types Guide
                </h3>
                <div class="text-sm text-blue-800 space-y-2">
                    <div><strong>Multiple Choice:</strong> Questions with multiple options where students select the correct answer(s)</div>
                    <div><strong>True/False:</strong> Simple questions with only two possible answers</div>
                    <div><strong>Short Answer:</strong> Questions requiring brief written responses</div>
                    <div><strong>Essay:</strong> Questions requiring detailed written explanations or analysis</div>
                </div>
                <div class="mt-4 text-sm text-blue-700">
                    <strong>Note:</strong> After creating the question, you can add specific answer options and mark correct answers in the question details page.
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
