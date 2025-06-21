@extends('layouts.admin')

@section('title', 'Edit Feedback')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Feedback</h1>
                    <p class="mt-1 text-sm text-gray-600">Update feedback information</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.feedback.show', $feedback) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i>View Feedback
                    </a>
                    <a href="{{ route('admin.feedback.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Feedback
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow">
            <form action="{{ route('admin.feedback.update', $feedback) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <!-- Current Feedback Info -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Current Feedback Information</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Feedback ID:</span>
                                <span class="text-gray-900 font-mono">{{ substr($feedback->feedback_id, 0, 8) }}...</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Created:</span>
                                <span class="text-gray-900">{{ $feedback->created_at->format('M d, Y g:i A') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Current Rating:</span>
                                <span class="text-gray-900 font-medium">{{ $feedback->rating }}/5 stars</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Selection -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">User Information</h3>
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">User *</label>
                        <select name="user_id" id="user_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('user_id') border-red-500 @enderror">
                            <option value="">Select User</option>
                            @foreach($users as $user)
                            <option value="{{ $user->user_id }}" {{ old('user_id', $feedback->user_id) === $user->user_id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }}) - {{ ucfirst($user->role) }}
                            </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Feedback Target -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Feedback Target</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="question_id" class="block text-sm font-medium text-gray-700 mb-2">Question (Optional)</label>
                            <select name="question_id" id="question_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('question_id') border-red-500 @enderror">
                                <option value="">Select Question</option>
                                @foreach($questions as $question)
                                <option value="{{ $question->question_id }}" {{ old('question_id', $feedback->question_id) === $question->question_id ? 'selected' : '' }}>
                                    {{ Str::limit($question->question_text, 60) }}
                                    @if($question->note)
                                        ({{ $question->note->title }})
                                    @endif
                                </option>
                                @endforeach
                            </select>
                            @error('question_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="answer_id" class="block text-sm font-medium text-gray-700 mb-2">Answer (Optional)</label>
                            <select name="answer_id" id="answer_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('answer_id') border-red-500 @enderror">
                                <option value="">Select Answer</option>
                                @foreach($answers as $answer)
                                <option value="{{ $answer->answer_id }}" {{ old('answer_id', $feedback->answer_id) === $answer->answer_id ? 'selected' : '' }}>
                                    {{ Str::limit($answer->answer_text, 60) }}
                                    @if($answer->question && $answer->question->note)
                                        ({{ $answer->question->note->title }})
                                    @endif
                                </option>
                                @endforeach
                            </select>
                            @error('answer_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Select either a question or answer that this feedback relates to, or leave both blank for general feedback.</p>
                </div>

                <!-- Rating -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Rating</h3>
                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">Rating *</label>
                        <div class="flex items-center space-x-4">
                            @for($i = 1; $i <= 5; $i++)
                            <label class="flex items-center">
                                <input type="radio" name="rating" value="{{ $i }}" {{ old('rating', $feedback->rating) == $i ? 'checked' : '' }} required
                                       class="sr-only peer">
                                <div class="flex items-center cursor-pointer">
                                    @for($j = 1; $j <= $i; $j++)
                                    <i class="fas fa-star text-yellow-400 text-xl peer-checked:text-yellow-500"></i>
                                    @endfor
                                    @for($j = $i + 1; $j <= 5; $j++)
                                    <i class="far fa-star text-gray-300 text-xl"></i>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-700">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</span>
                                </div>
                            </label>
                            @endfor
                        </div>
                        @error('rating')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Comments -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Comments</h3>
                    <div>
                        <label for="comments" class="block text-sm font-medium text-gray-700 mb-2">Feedback Comments</label>
                        <textarea name="comments" id="comments" rows="6" 
                                  placeholder="Enter detailed feedback comments..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('comments') border-red-500 @enderror">{{ old('comments', $feedback->comments) }}</textarea>
                        @error('comments')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Maximum 1000 characters</p>
                    </div>
                </div>

                <!-- Current Values Display -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Current Values</h3>
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-blue-600 font-medium">Current User:</span>
                                <span class="text-blue-800">{{ $feedback->user->name }}</span>
                            </div>
                            <div>
                                <span class="text-blue-600 font-medium">Current Rating:</span>
                                <span class="text-blue-800">{{ $feedback->rating }}/5 stars</span>
                            </div>
                            @if($feedback->question)
                            <div class="md:col-span-2">
                                <span class="text-blue-600 font-medium">Current Question:</span>
                                <span class="text-blue-800">{{ Str::limit($feedback->question->question_text, 100) }}</span>
                            </div>
                            @endif
                            @if($feedback->answer)
                            <div class="md:col-span-2">
                                <span class="text-blue-600 font-medium">Current Answer:</span>
                                <span class="text-blue-800">{{ Str::limit($feedback->answer->answer_text, 100) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.feedback.show', $feedback) }}" 
                       class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Update Feedback
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Star rating interaction
    document.querySelectorAll('input[name="rating"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const rating = this.value;
            
            // Update all star displays
            document.querySelectorAll('input[name="rating"]').forEach((r, index) => {
                const stars = r.parentElement.querySelectorAll('i');
                stars.forEach((star, starIndex) => {
                    if (starIndex < rating) {
                        star.className = 'fas fa-star text-yellow-500 text-xl';
                    } else {
                        star.className = 'far fa-star text-gray-300 text-xl';
                    }
                });
            });
        });
    });

    // Initialize star display on page load
    const currentRating = {{ $feedback->rating }};
    document.querySelectorAll('input[name="rating"]').forEach((r, index) => {
        const stars = r.parentElement.querySelectorAll('i');
        stars.forEach((star, starIndex) => {
            if (starIndex < currentRating) {
                star.className = 'fas fa-star text-yellow-500 text-xl';
            } else {
                star.className = 'far fa-star text-gray-300 text-xl';
            }
        });
    });
</script>
@endsection
