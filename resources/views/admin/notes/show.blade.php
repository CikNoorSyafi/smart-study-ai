@extends('layouts.admin')

@section('title', 'View Note')
@section('page-title', 'Note Details')

@section('content')
<div class="p-6">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $note->title }}</h1>
                    <p class="mt-2 text-gray-600">Note Details and Management</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.notes-crud.edit', $note) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit Note
                    </a>
                    <a href="{{ route('admin.notes-crud.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Notes
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Note Content -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Content</h3>
                    </div>
                    <div class="p-6">
                        <div class="prose max-w-none">
                            {!! nl2br(e($note->content)) !!}
                        </div>
                    </div>
                </div>

                <!-- Associated Questions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Generated Questions ({{ $note->questions->count() }})
                        </h3>
                        <a href="{{ route('admin.questions.create') }}?note_id={{ $note->note_id }}" 
                           class="bg-green-600 text-white px-3 py-1 rounded-md text-sm hover:bg-green-700 transition-colors">
                            <i class="fas fa-plus mr-1"></i>Add Question
                        </a>
                    </div>
                    <div class="p-6">
                        @if($note->questions->count() > 0)
                            <div class="space-y-4">
                                @foreach($note->questions->take(5) as $question)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ Str::limit($question->question_text, 100) }}
                                                </p>
                                                <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500">
                                                    <span>
                                                        <i class="fas fa-user mr-1"></i>
                                                        {{ $question->user->name ?? 'Unknown' }}
                                                    </span>
                                                    <span>
                                                        <i class="fas fa-calendar mr-1"></i>
                                                        {{ $question->created_at->format('M d, Y') }}
                                                    </span>
                                                    <span>
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        {{ $question->answers->count() }} answers
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2 ml-4">
                                                <a href="{{ route('admin.questions.show', $question) }}" 
                                                   class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.questions.edit', $question) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                
                                @if($note->questions->count() > 5)
                                    <div class="text-center pt-4">
                                        <a href="{{ route('admin.questions.index') }}?note_id={{ $note->note_id }}" 
                                           class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                            View all {{ $note->questions->count() }} questions →
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-question-circle text-gray-400 text-3xl mb-4"></i>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">No Questions Yet</h4>
                                <p class="text-gray-500 mb-4">Generate intelligent questions from this note content automatically.</p>
                                <div class="space-y-3">
                                    <button onclick="showQuestionGeneratorModal()"
                                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                        <i class="fas fa-magic mr-2"></i>Generate Questions
                                    </button>
                                    <br>
                                    <a href="{{ route('admin.questions.create') }}?note_id={{ $note->note_id }}"
                                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-plus mr-2"></i>Create Manual Question
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Note Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Note Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                @if($note->status === 'published') bg-green-100 text-green-800
                                @elseif($note->status === 'draft') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($note->status) }}
                            </span>
                        </div>

                        <!-- Author -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Author</label>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-sm font-medium text-blue-600">{{ substr($note->user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $note->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ ucfirst($note->user->role) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Dates -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                            <p class="text-sm text-gray-900">{{ $note->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                            <p class="text-sm text-gray-900">{{ $note->updated_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>

                        <!-- Word Count -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Word Count</label>
                            <p class="text-sm text-gray-900">{{ $note->word_count ?? str_word_count($note->content) }} words</p>
                        </div>
                    </div>
                </div>

                <!-- Associated Subjects -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Associated Subjects</h3>
                    </div>
                    <div class="p-6">
                        @if($note->subjects->count() > 0)
                            <div class="space-y-2">
                                @foreach($note->subjects as $subject)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-book text-blue-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $subject->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $subject->description ?? 'No description' }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.subjects.show', $subject) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-external-link-alt text-sm"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-book text-gray-400 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-500">No subjects associated</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <button onclick="showQuestionGeneratorModal()"
                                class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-magic mr-2"></i>Generate Questions
                        </button>

                        <a href="{{ route('admin.questions.create') }}?note_id={{ $note->note_id }}"
                           class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Create Manual Question
                        </a>
                        
                        <a href="{{ route('admin.notes-crud.edit', $note) }}" 
                           class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-edit mr-2"></i>Edit Note
                        </a>
                        
                        <button onclick="duplicateNote()" 
                                class="w-full flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                            <i class="fas fa-copy mr-2"></i>Duplicate Note
                        </button>
                        
                        <form action="{{ route('admin.notes-crud.destroy', $note) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this note? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                <i class="fas fa-trash mr-2"></i>Delete Note
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Statistics</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Questions Generated</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $note->questions->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Total Answers</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $note->questions->sum(function($q) { return $q->answers->count(); }) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Associated Subjects</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $note->subjects->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Content Length</span>
                            <span class="text-sm font-semibold text-gray-900">{{ strlen($note->content) }} chars</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Question Generator Modal -->
<div id="questionGeneratorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Generate Questions</h3>
                <button onclick="closeQuestionGeneratorModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="questionGeneratorForm">
                <div class="mb-4">
                    <label for="questionCount" class="block text-sm font-medium text-gray-700 mb-2">
                        Number of Questions
                    </label>
                    <select id="questionCount" name="count" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="3">3 Questions</option>
                        <option value="5" selected>5 Questions</option>
                        <option value="7">7 Questions</option>
                        <option value="10">10 Questions</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="questionDifficulty" class="block text-sm font-medium text-gray-700 mb-2">
                        Difficulty Level
                    </label>
                    <select id="questionDifficulty" name="difficulty" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="easy">Easy</option>
                        <option value="medium" selected>Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>

                <div class="mb-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="flex">
                            <i class="fas fa-info-circle text-blue-400 mt-0.5 mr-2"></i>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium mb-1">Intelligent Question Generation</p>
                                <p>Questions will be generated based on the actual content of this note, including key concepts, facts, definitions, and important information.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeQuestionGeneratorModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="generateBtn"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        <i class="fas fa-magic mr-2"></i>Generate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div id="loadingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-1/2 mx-auto p-5 border w-80 shadow-lg rounded-md bg-white transform -translate-y-1/2">
        <div class="text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600 mx-auto mb-4"></div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Generating Questions...</h3>
            <p class="text-sm text-gray-600">Analyzing note content and creating intelligent questions.</p>
        </div>
    </div>
</div>

<!-- Question Verification Modal -->
<div id="verificationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Review Generated Questions</h3>
                <button onclick="closeVerificationModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="mb-4">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <div class="flex">
                        <i class="fas fa-info-circle text-blue-400 mt-0.5 mr-2"></i>
                        <div class="text-sm text-blue-800">
                            <p class="font-medium mb-1">Question Verification</p>
                            <p>Review the generated questions below. You can approve good questions or reject poor ones. Only approved questions will be added to your question bank.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="generatedQuestionsList" class="space-y-4 mb-6 max-h-96 overflow-y-auto">
                <!-- Generated questions will be populated here -->
            </div>

            <div class="flex items-center justify-between">
                <div class="flex space-x-3">
                    <button onclick="closeVerificationModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                </div>
                <div class="flex space-x-3">
                    <button onclick="rejectSelectedQuestions()" id="rejectBtn"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        <i class="fas fa-times mr-2"></i>Reject Selected
                    </button>
                    <button onclick="approveSelectedQuestions()" id="approveBtn"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        <i class="fas fa-check mr-2"></i>Approve Selected
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function showQuestionGeneratorModal() {
    document.getElementById('questionGeneratorModal').classList.remove('hidden');
}

function closeQuestionGeneratorModal() {
    document.getElementById('questionGeneratorModal').classList.add('hidden');
}

function showLoadingModal() {
    document.getElementById('loadingModal').classList.remove('hidden');
}

function hideLoadingModal() {
    document.getElementById('loadingModal').classList.add('hidden');
}

function showVerificationModal() {
    document.getElementById('verificationModal').classList.remove('hidden');
}

function closeVerificationModal() {
    document.getElementById('verificationModal').classList.add('hidden');
}

document.getElementById('questionGeneratorForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const count = formData.get('count');
    const difficulty = formData.get('difficulty');

    // Close generator modal and show loading
    closeQuestionGeneratorModal();
    showLoadingModal();

    // Make AJAX request
    fetch('{{ route("admin.notes-crud.generate-questions", $note) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            count: parseInt(count),
            difficulty: difficulty
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoadingModal();

        if (data.success) {
            if (data.requires_verification) {
                // Show verification modal with generated questions
                populateVerificationModal(data.questions);
                showVerificationModal();
            } else {
                // Show success message and reload
                showNotification('success', data.message);
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        } else {
            showNotification('error', data.message);
        }
    })
    .catch(error => {
        hideLoadingModal();
        console.error('Error:', error);
        showNotification('error', 'Failed to generate questions. Please try again.');
    });
});

function showNotification(type, message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
        type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white'
    }`;

    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    // Remove notification after 5 seconds
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

function populateVerificationModal(questions) {
    const container = document.getElementById('generatedQuestionsList');
    container.innerHTML = '';

    questions.forEach((question, index) => {
        const questionHtml = `
            <div class="border border-gray-200 rounded-lg p-4 bg-white">
                <div class="flex items-start space-x-3">
                    <input type="checkbox" id="question_${question.id}" value="${question.id}"
                           class="question-checkbox mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" checked>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">${question.type.replace('_', ' ')}</span>
                            <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">${question.difficulty}</span>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">${question.text}</h4>
                        ${question.explanation ? `<p class="text-sm text-gray-600 mb-3">${question.explanation}</p>` : ''}

                        <div class="space-y-2">
                            <h5 class="text-sm font-medium text-gray-700">Answers:</h5>
                            ${question.answers.map(answer => `
                                <div class="flex items-center space-x-2 text-sm">
                                    <i class="fas ${answer.is_correct ? 'fa-check-circle text-green-600' : 'fa-circle text-gray-400'}"></i>
                                    <span class="${answer.is_correct ? 'text-green-700 font-medium' : 'text-gray-700'}">${answer.text}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += questionHtml;
    });
}

function getSelectedQuestionIds() {
    const checkboxes = document.querySelectorAll('.question-checkbox:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

function approveSelectedQuestions() {
    const questionIds = getSelectedQuestionIds();

    if (questionIds.length === 0) {
        showNotification('error', 'Please select at least one question to approve.');
        return;
    }

    fetch('{{ route("admin.questions.approve") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            question_ids: questionIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            closeVerificationModal();
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showNotification('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Failed to approve questions. Please try again.');
    });
}

function rejectSelectedQuestions() {
    const questionIds = getSelectedQuestionIds();

    if (questionIds.length === 0) {
        showNotification('error', 'Please select at least one question to reject.');
        return;
    }

    if (!confirm('Are you sure you want to reject the selected questions? They will be permanently deleted.')) {
        return;
    }

    fetch('{{ route("admin.questions.reject") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            question_ids: questionIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            closeVerificationModal();
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showNotification('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Failed to reject questions. Please try again.');
    });
}

// Close modals when clicking outside
document.getElementById('questionGeneratorModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeQuestionGeneratorModal();
    }
});

document.getElementById('verificationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeVerificationModal();
    }
});
</script>
@endsection

@section('scripts')
<script>
    function duplicateNote() {
        if (confirm('Create a copy of this note?')) {
            // In a real implementation, this would make an AJAX call to duplicate the note
            window.location.href = "{{ route('admin.notes-crud.create') }}?duplicate={{ $note->note_id }}";
        }
    }
</script>
@endsection
