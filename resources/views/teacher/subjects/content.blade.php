@extends('teacher.layout')

@section('title', $subject->name . ' - Content')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <a href="{{ route('teacher.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-home"></i>
                        <span class="sr-only">Dashboard</span>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('teacher.subjects.detail', $subject) }}" class="text-gray-500 hover:text-gray-700">{{ $subject->name }}</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-gray-900">Content</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-3xl font-bold text-gray-900 mt-2">{{ $subject->name }} - Content</h1>
        <p class="mt-1 text-sm text-gray-600">Manage study notes and questions for this subject.</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('teacher.notes.create') }}?subject={{ $subject->subject_id }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
            <i class="fas fa-plus mr-2"></i>Create Note
        </a>
        <a href="{{ route('teacher.subjects.detail', $subject) }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Subject
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <!-- Content Tabs -->
    <div class="bg-white shadow rounded-lg">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button onclick="showTab('notes')" id="notes-tab" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <i class="fas fa-file-alt mr-2"></i>
                    Study Notes ({{ $notes->total() }})
                </button>
                <button onclick="showTab('questions')" id="questions-tab" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <i class="fas fa-question-circle mr-2"></i>
                    Questions ({{ $questions->total() }})
                </button>
            </nav>
        </div>

        <!-- Notes Tab Content -->
        <div id="notes-content" class="tab-content p-6">
            @if($notes->count() > 0)
                <div class="space-y-4">
                    @foreach($notes as $note)
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $note->title }}</h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($note->status === 'published') bg-green-100 text-green-800 
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($note->status) }}
                                    </span>
                                </div>
                                <p class="text-gray-600 mb-4">{{ Str::limit($note->content, 200) }}</p>
                                <div class="flex items-center space-x-6 text-sm text-gray-500">
                                    <span>
                                        <i class="fas fa-calendar mr-1"></i>
                                        Created {{ $note->created_at->format('M j, Y') }}
                                    </span>
                                    <span>
                                        <i class="fas fa-eye mr-1"></i>
                                        {{ rand(10, 50) }} views
                                    </span>
                                    <span>
                                        <i class="fas fa-question-circle mr-1"></i>
                                        {{ $note->questions->count() }} questions
                                    </span>
                                </div>
                            </div>
                            <div class="ml-6 flex space-x-2">
                                <a href="{{ route('teacher.notes.show', $note) }}" class="text-blue-600 hover:text-blue-800" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('teacher.notes.edit', $note) }}" class="text-green-600 hover:text-green-800" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="generateQuestions('{{ $note->note_id }}')" class="text-purple-600 hover:text-purple-800" title="Generate Questions">
                                    <i class="fas fa-magic"></i>
                                </button>
                                <button onclick="deleteNote('{{ $note->note_id }}')" class="text-red-600 hover:text-red-800" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $notes->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-file-alt text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No study notes yet</h3>
                    <p class="text-gray-500 mb-4">Create your first study note for {{ $subject->name }}.</p>
                    <a href="{{ route('teacher.notes.create') }}?subject={{ $subject->subject_id }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Create First Note
                    </a>
                </div>
            @endif
        </div>

        <!-- Questions Tab Content -->
        <div id="questions-content" class="tab-content p-6 hidden">
            @if($questions->count() > 0)
                <div class="space-y-4">
                    @foreach($questions as $question)
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $question->question_text }}</h3>
                                <p class="text-sm text-gray-600 mb-3">From note: <span class="font-medium">{{ $question->note->title }}</span></p>
                                <div class="space-y-2">
                                    @foreach($question->answers as $answer)
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-medium mr-3
                                            @if($answer->is_correct) bg-green-100 text-green-800 @else bg-gray-100 text-gray-600 @endif">
                                            {{ chr(65 + $loop->index) }}
                                        </span>
                                        <span class="text-sm @if($answer->is_correct) text-green-800 font-medium @else text-gray-700 @endif">
                                            {{ $answer->answer_text }}
                                        </span>
                                        @if($answer->is_correct)
                                        <i class="fas fa-check text-green-600 ml-2"></i>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                <div class="flex items-center space-x-6 text-sm text-gray-500 mt-4">
                                    <span>
                                        <i class="fas fa-calendar mr-1"></i>
                                        Created {{ $question->created_at->format('M j, Y') }}
                                    </span>
                                    <span>
                                        <i class="fas fa-chart-bar mr-1"></i>
                                        {{ rand(5, 25) }} responses
                                    </span>
                                </div>
                            </div>
                            <div class="ml-6 flex space-x-2">
                                <button onclick="editQuestion('{{ $question->question_id }}')" class="text-green-600 hover:text-green-800" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="duplicateQuestion('{{ $question->question_id }}')" class="text-blue-600 hover:text-blue-800" title="Duplicate">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <button onclick="deleteQuestion('{{ $question->question_id }}')" class="text-red-600 hover:text-red-800" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $questions->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-question-circle text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No questions yet</h3>
                    <p class="text-gray-500 mb-4">Generate questions from your study notes or create custom ones.</p>
                    <div class="space-x-3">
                        <a href="{{ route('teacher.questions.create') }}?subject={{ $subject->subject_id }}" class="bg-secondary text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Create Question
                        </a>
                        @if($notes->count() > 0)
                        <button onclick="generateFromNotes()" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                            <i class="fas fa-magic mr-2"></i>Generate from Notes
                        </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Tab functionality
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(tab => {
        tab.classList.remove('border-primary', 'text-primary');
        tab.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-content').classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-primary', 'text-primary');
}

// Initialize first tab as active
document.addEventListener('DOMContentLoaded', function() {
    showTab('notes');
});

// Note actions
function generateQuestions(noteId) {
    alert('Generate questions functionality will be implemented for note: ' + noteId);
}

function deleteNote(noteId) {
    if (confirm('Are you sure you want to delete this note? This action cannot be undone.')) {
        alert('Delete note functionality will be implemented for note: ' + noteId);
    }
}

// Question actions
function editQuestion(questionId) {
    alert('Edit question functionality will be implemented for question: ' + questionId);
}

function duplicateQuestion(questionId) {
    alert('Duplicate question functionality will be implemented for question: ' + questionId);
}

function deleteQuestion(questionId) {
    if (confirm('Are you sure you want to delete this question?')) {
        alert('Delete question functionality will be implemented for question: ' + questionId);
    }
}

function generateFromNotes() {
    alert('Generate questions from notes functionality will be implemented.');
}
</script>
@endsection
