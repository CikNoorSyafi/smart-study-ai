@extends('teacher.layout')

@section('title', $note->title)

@section('header')
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">{{ $note->title }}</h1>
        <div class="mt-1 flex items-center space-x-4 text-sm text-gray-600">
            <span>
                <i class="fas fa-calendar mr-1"></i>
                Created {{ $note->created_at->format('M j, Y') }}
            </span>
            <span>
                <i class="fas fa-clock mr-1"></i>
                Updated {{ $note->updated_at->diffForHumans() }}
            </span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                @if($note->status === 'published') bg-green-100 text-green-800 
                @else bg-yellow-100 text-yellow-800 @endif">
                {{ ucfirst($note->status) }}
            </span>
        </div>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('teacher.notes.edit', $note) }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
            <i class="fas fa-edit mr-2"></i>Edit Note
        </a>
        <a href="{{ route('teacher.content') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Content
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Note Content -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="prose max-w-none">
            {!! nl2br(e($note->content)) !!}
        </div>
    </div>

    <!-- Note Metadata -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Assigned Subjects -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-book mr-2 text-primary"></i>
                Assigned Subjects
            </h3>
            @if($note->subjects->count() > 0)
                <div class="space-y-2">
                    @foreach($note->subjects as $subject)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <div class="font-medium text-gray-900">{{ $subject->name }}</div>
                            @if($subject->description)
                            <div class="text-sm text-gray-500">{{ Str::limit($subject->description, 100) }}</div>
                            @endif
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Subject
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">
                    <i class="fas fa-info-circle mr-2"></i>
                    This note is not assigned to any subjects yet.
                </p>
            @endif
        </div>

        <!-- Note Statistics -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-chart-bar mr-2 text-primary"></i>
                Note Statistics
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Questions</span>
                    <span class="font-medium text-gray-900">{{ $note->questions->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Word Count</span>
                    <span class="font-medium text-gray-900">{{ str_word_count($note->content) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Character Count</span>
                    <span class="font-medium text-gray-900">{{ strlen($note->content) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Reading Time</span>
                    <span class="font-medium text-gray-900">{{ ceil(str_word_count($note->content) / 200) }} min</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Generated Questions -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-question-circle mr-2 text-primary"></i>
                Generated Questions ({{ $note->questions->count() }})
            </h3>
            <a href="{{ route('teacher.questions.create') }}?note_id={{ $note->note_id }}" 
               class="bg-secondary text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
                <i class="fas fa-plus mr-2"></i>Add Question
            </a>
        </div>

        @if($note->questions->count() > 0)
            <div class="space-y-4">
                @foreach($note->questions as $question)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900 mb-2">{{ $question->question_text }}</h4>
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span>
                                    <i class="fas fa-list mr-1"></i>
                                    {{ $question->answers->count() }} answers
                                </span>
                                <span>
                                    <i class="fas fa-check-circle mr-1"></i>
                                    {{ $question->answers->where('is_correct', true)->count() }} correct
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                    @if($question->difficulty === 'easy') bg-green-100 text-green-800
                                    @elseif($question->difficulty === 'medium') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($question->difficulty) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('teacher.questions.edit', $question) }}" 
                               class="text-primary hover:text-blue-600" title="Edit Question">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteQuestion('{{ $question->question_id }}')" 
                                    class="text-red-500 hover:text-red-700" title="Delete Question">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Answers Preview -->
                    @if($question->answers->count() > 0)
                    <div class="mt-3 space-y-1">
                        @foreach($question->answers->take(4) as $answer)
                        <div class="flex items-center text-sm">
                            <span class="w-6 h-6 rounded-full border-2 flex items-center justify-center mr-2
                                {{ $answer->is_correct ? 'border-green-500 bg-green-100 text-green-700' : 'border-gray-300' }}">
                                {{ chr(65 + $loop->index) }}
                            </span>
                            <span class="{{ $answer->is_correct ? 'text-green-700 font-medium' : 'text-gray-600' }}">
                                {{ Str::limit($answer->answer_text, 80) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-question-circle text-4xl text-gray-300 mb-4"></i>
                <h4 class="text-lg font-medium text-gray-900 mb-2">No Questions Generated Yet</h4>
                <p class="text-gray-500 mb-4">Generate questions from this note to help students practice.</p>
                <a href="{{ route('teacher.questions.create') }}?note_id={{ $note->note_id }}" 
                   class="bg-secondary text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Generate First Question
                </a>
            </div>
        @endif
    </div>

    <!-- Action Buttons -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div class="flex space-x-3">
                <a href="{{ route('teacher.notes.edit', $note) }}" 
                   class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit Note
                </a>
                <a href="{{ route('teacher.questions.create') }}?note_id={{ $note->note_id }}" 
                   class="bg-secondary text-white px-6 py-2 rounded-lg hover:bg-green-600 transition-colors">
                    <i class="fas fa-question-circle mr-2"></i>Add Questions
                </a>
                <button onclick="duplicateNote()" 
                        class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-copy mr-2"></i>Duplicate
                </button>
            </div>
            <div class="flex space-x-3">
                <button onclick="shareNote()" 
                        class="text-blue-500 hover:text-blue-700 transition-colors">
                    <i class="fas fa-share mr-1"></i>Share
                </button>
                <button onclick="confirmDelete()" 
                        class="text-red-500 hover:text-red-700 transition-colors">
                    <i class="fas fa-trash mr-1"></i>Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Hidden Forms -->
    <form id="delete-note-form" action="{{ route('teacher.notes.delete', $note) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    @foreach($note->questions as $question)
    <form id="delete-question-form-{{ $question->question_id }}" 
          action="{{ route('teacher.questions.delete', $question) }}" 
          method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    @endforeach
</div>
@endsection

@section('scripts')
<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this note? This action cannot be undone and will also delete all associated questions.')) {
        document.getElementById('delete-note-form').submit();
    }
}

function deleteQuestion(questionId) {
    if (confirm('Are you sure you want to delete this question? This action cannot be undone.')) {
        document.getElementById('delete-question-form-' + questionId).submit();
    }
}

function duplicateNote() {
    // Redirect to create form with pre-filled data
    const url = new URL('{{ route("teacher.notes.create") }}');
    url.searchParams.set('duplicate', '{{ $note->note_id }}');
    window.location.href = url.toString();
}

function shareNote() {
    // Copy note URL to clipboard
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(() => {
        // Show success message
        const indicator = document.createElement('div');
        indicator.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded text-sm';
        indicator.textContent = 'Note URL copied to clipboard!';
        document.body.appendChild(indicator);
        
        setTimeout(() => {
            indicator.remove();
        }, 3000);
    }).catch(() => {
        // Fallback for older browsers
        prompt('Copy this URL to share the note:', url);
    });
}
</script>
@endsection
