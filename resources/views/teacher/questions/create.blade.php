@extends('teacher.layout')

@section('title', 'Create Question')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Create Question</h1>
        <p class="mt-1 text-sm text-gray-600">Create a new question for your study notes.</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('teacher.content') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Content
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('teacher.questions.store') }}" method="POST" class="space-y-6" id="question-form">
        @csrf
        
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Question Details</h3>
            
            <!-- Note Selection -->
            <div class="mb-6">
                <label for="note_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Study Note <span class="text-red-500">*</span>
                </label>
                <select id="note_id" 
                        name="note_id" 
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('note_id') border-red-500 @enderror"
                        required>
                    <option value="">Select a study note...</option>
                    @foreach($notes as $note)
                    <option value="{{ $note->note_id }}" 
                            {{ old('note_id', $selectedNoteId) === $note->note_id ? 'selected' : '' }}>
                        {{ $note->title }} 
                        <span class="text-gray-500">({{ ucfirst($note->status) }})</span>
                    </option>
                    @endforeach
                </select>
                @error('note_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Question Text -->
            <div class="mb-6">
                <label for="question_text" class="block text-sm font-medium text-gray-700 mb-2">
                    Question <span class="text-red-500">*</span>
                </label>
                <textarea id="question_text" 
                          name="question_text" 
                          rows="3"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('question_text') border-red-500 @enderror"
                          placeholder="Enter your question here..."
                          required>{{ old('question_text') }}</textarea>
                @error('question_text')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Difficulty -->
            <div class="mb-6">
                <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-2">
                    Difficulty Level <span class="text-red-500">*</span>
                </label>
                <select id="difficulty" 
                        name="difficulty" 
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('difficulty') border-red-500 @enderror"
                        required>
                    <option value="">Select difficulty...</option>
                    <option value="easy" {{ old('difficulty') === 'easy' ? 'selected' : '' }}>Easy</option>
                    <option value="medium" {{ old('difficulty') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="hard" {{ old('difficulty') === 'hard' ? 'selected' : '' }}>Hard</option>
                </select>
                @error('difficulty')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Answer Options -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-gray-900">Answer Options</h3>
                <button type="button" onclick="addAnswer()" class="bg-secondary text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add Answer
                </button>
            </div>
            
            <div id="answers-container">
                <!-- Initial answer options -->
                @for($i = 0; $i < 4; $i++)
                <div class="answer-option mb-4 p-4 border border-gray-200 rounded-lg" data-index="{{ $i }}">
                    <div class="flex items-start space-x-3">
                        <div class="flex items-center mt-2">
                            <input type="radio" 
                                   name="correct_answer" 
                                   value="{{ $i }}" 
                                   class="text-primary focus:ring-primary"
                                   {{ old('correct_answer') == $i ? 'checked' : '' }}>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Answer {{ chr(65 + $i) }}
                                @if($i < 2) <span class="text-red-500">*</span> @endif
                            </label>
                            <input type="text" 
                                   name="answers[{{ $i }}][text]" 
                                   value="{{ old('answers.' . $i . '.text') }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="Enter answer option..."
                                   {{ $i < 2 ? 'required' : '' }}>
                            <input type="hidden" name="answers[{{ $i }}][is_correct]" value="0">
                        </div>
                        @if($i >= 2)
                        <button type="button" onclick="removeAnswer(this)" class="text-red-500 hover:text-red-700 mt-8">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endif
                    </div>
                </div>
                @endfor
            </div>
            
            <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-2"></i>
                    <div class="text-sm text-blue-700">
                        <p class="font-medium mb-1">Instructions:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Select the radio button next to the correct answer</li>
                            <li>At least 2 answer options are required</li>
                            <li>You can add up to 6 answer options</li>
                            <li>Only one answer can be marked as correct</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center">
                <div class="flex space-x-3">
                    <button type="submit" 
                            class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                        <i class="fas fa-save mr-2"></i>Create Question
                    </button>
                    <button type="button" 
                            onclick="previewQuestion()"
                            class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-eye mr-2"></i>Preview
                    </button>
                </div>
                <a href="{{ route('teacher.content') }}" 
                   class="text-gray-500 hover:text-gray-700 transition-colors">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Preview Modal -->
<div id="preview-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Question Preview</h3>
                <button onclick="closePreview()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="preview-content">
                <!-- Preview content will be inserted here -->
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="closePreview()" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                    Close Preview
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let answerCount = 4;

function addAnswer() {
    if (answerCount >= 6) {
        alert('Maximum 6 answer options allowed.');
        return;
    }
    
    const container = document.getElementById('answers-container');
    const answerDiv = document.createElement('div');
    answerDiv.className = 'answer-option mb-4 p-4 border border-gray-200 rounded-lg';
    answerDiv.setAttribute('data-index', answerCount);
    
    answerDiv.innerHTML = `
        <div class="flex items-start space-x-3">
            <div class="flex items-center mt-2">
                <input type="radio" 
                       name="correct_answer" 
                       value="${answerCount}" 
                       class="text-primary focus:ring-primary">
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Answer ${String.fromCharCode(65 + answerCount)}
                </label>
                <input type="text" 
                       name="answers[${answerCount}][text]" 
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                       placeholder="Enter answer option...">
                <input type="hidden" name="answers[${answerCount}][is_correct]" value="0">
            </div>
            <button type="button" onclick="removeAnswer(this)" class="text-red-500 hover:text-red-700 mt-8">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    
    container.appendChild(answerDiv);
    answerCount++;
}

function removeAnswer(button) {
    const answerDiv = button.closest('.answer-option');
    const currentAnswers = document.querySelectorAll('.answer-option').length;
    
    if (currentAnswers <= 2) {
        alert('At least 2 answer options are required.');
        return;
    }
    
    answerDiv.remove();
    answerCount--;
    
    // Update answer labels
    updateAnswerLabels();
}

function updateAnswerLabels() {
    const answerOptions = document.querySelectorAll('.answer-option');
    answerOptions.forEach((option, index) => {
        const label = option.querySelector('label');
        const radio = option.querySelector('input[type="radio"]');
        const textInput = option.querySelector('input[type="text"]');
        const hiddenInput = option.querySelector('input[type="hidden"]');
        
        label.innerHTML = `Answer ${String.fromCharCode(65 + index)}${index < 2 ? ' <span class="text-red-500">*</span>' : ''}`;
        radio.value = index;
        textInput.name = `answers[${index}][text]`;
        hiddenInput.name = `answers[${index}][is_correct]`;
        
        if (index < 2) {
            textInput.required = true;
        } else {
            textInput.required = false;
        }
    });
}

function previewQuestion() {
    const questionText = document.getElementById('question_text').value;
    const difficulty = document.getElementById('difficulty').value;
    const answers = [];
    const correctAnswer = document.querySelector('input[name="correct_answer"]:checked');
    
    document.querySelectorAll('.answer-option').forEach((option, index) => {
        const text = option.querySelector('input[type="text"]').value;
        if (text.trim()) {
            answers.push({
                text: text,
                isCorrect: correctAnswer && correctAnswer.value == index
            });
        }
    });
    
    if (!questionText.trim()) {
        alert('Please enter a question.');
        return;
    }
    
    if (answers.length < 2) {
        alert('Please provide at least 2 answer options.');
        return;
    }
    
    if (!correctAnswer) {
        alert('Please select the correct answer.');
        return;
    }
    
    // Generate preview HTML
    let previewHTML = `
        <div class="space-y-4">
            <div class="p-4 bg-gray-50 rounded-lg">
                <div class="flex justify-between items-start mb-2">
                    <h4 class="font-medium text-gray-900">Question:</h4>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                        ${difficulty === 'easy' ? 'bg-green-100 text-green-800' : 
                          difficulty === 'medium' ? 'bg-yellow-100 text-yellow-800' : 
                          'bg-red-100 text-red-800'}">
                        ${difficulty ? difficulty.charAt(0).toUpperCase() + difficulty.slice(1) : 'Not selected'}
                    </span>
                </div>
                <p class="text-gray-700">${questionText}</p>
            </div>
            <div class="space-y-2">
                <h5 class="font-medium text-gray-900">Answer Options:</h5>
    `;
    
    answers.forEach((answer, index) => {
        previewHTML += `
            <div class="flex items-center p-3 border rounded-lg ${answer.isCorrect ? 'border-green-500 bg-green-50' : 'border-gray-200'}">
                <span class="w-6 h-6 rounded-full border-2 flex items-center justify-center mr-3 ${answer.isCorrect ? 'border-green-500 bg-green-100 text-green-700' : 'border-gray-300'}">
                    ${String.fromCharCode(65 + index)}
                </span>
                <span class="${answer.isCorrect ? 'text-green-700 font-medium' : 'text-gray-700'}">${answer.text}</span>
                ${answer.isCorrect ? '<i class="fas fa-check text-green-500 ml-auto"></i>' : ''}
            </div>
        `;
    });
    
    previewHTML += '</div></div>';
    
    document.getElementById('preview-content').innerHTML = previewHTML;
    document.getElementById('preview-modal').classList.remove('hidden');
}

function closePreview() {
    document.getElementById('preview-modal').classList.add('hidden');
}

// Handle correct answer selection
document.addEventListener('change', function(e) {
    if (e.target.name === 'correct_answer') {
        // Update hidden inputs
        document.querySelectorAll('input[name*="[is_correct]"]').forEach(input => {
            input.value = '0';
        });
        
        const selectedIndex = e.target.value;
        const selectedHidden = document.querySelector(`input[name="answers[${selectedIndex}][is_correct]"]`);
        if (selectedHidden) {
            selectedHidden.value = '1';
        }
    }
});

// Form validation
document.getElementById('question-form').addEventListener('submit', function(e) {
    const correctAnswer = document.querySelector('input[name="correct_answer"]:checked');
    if (!correctAnswer) {
        e.preventDefault();
        alert('Please select the correct answer.');
        return;
    }
    
    const filledAnswers = Array.from(document.querySelectorAll('input[name*="[text]"]'))
        .filter(input => input.value.trim()).length;
    
    if (filledAnswers < 2) {
        e.preventDefault();
        alert('Please provide at least 2 answer options.');
        return;
    }
});
</script>
@endsection
