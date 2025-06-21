@extends('teacher.layout')

@section('title', 'My Content')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">My Content</h1>
        <p class="mt-1 text-sm text-gray-600">Create and manage your study notes and questions.</p>
    </div>
    <div class="flex space-x-3">
        <button class="bg-secondary text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
            <i class="fas fa-question-circle mr-2"></i>Generate Questions
        </button>
        <a href="{{ route('teacher.notes.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
            <i class="fas fa-plus mr-2"></i>Create Note
        </a>
    </div>
</div>
@endsection

@section('content')
<!-- Content Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Notes</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $notes->total() }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-check-circle text-white"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Published</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $notes->where('status', 'published')->count() }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-edit text-white"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Drafts</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $notes->where('status', 'draft')->count() }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-question-circle text-white"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Questions</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $notes->sum('questions_count') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content List -->
<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Study Notes</h3>
            <div class="flex space-x-2">
                <select class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option>All Status</option>
                    <option>Published</option>
                    <option>Draft</option>
                    <option>Archived</option>
                </select>
                <select class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option>All Subjects</option>
                    @foreach(auth()->user()->subjects()->wherePivot('role_in_subject', 'teacher')->get() as $subject)
                    <option value="{{ $subject->subject_id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
                <div class="relative">
                    <input type="text" placeholder="Search notes..." class="border border-gray-300 rounded-md pl-10 pr-4 py-2 text-sm">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
        </div>
    </div>

    @if($notes->count() > 0)
    <div class="divide-y divide-gray-200">
        @foreach($notes as $note)
        <div class="p-6 hover:bg-gray-50 transition-colors">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-2">
                        <h4 class="text-lg font-medium text-gray-900">{{ $note->title }}</h4>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($note->status === 'published') bg-green-100 text-green-800 
                            @elseif($note->status === 'draft') bg-yellow-100 text-yellow-800 
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($note->status) }}
                        </span>
                    </div>
                    
                    <p class="text-gray-600 mb-3">{{ Str::limit($note->content, 200) }}</p>
                    
                    <div class="flex items-center space-x-6 text-sm text-gray-500">
                        <div class="flex items-center">
                            <i class="fas fa-book mr-1"></i>
                            <span>
                                @if($note->subjects->count() > 0)
                                    {{ $note->subjects->pluck('name')->join(', ') }}
                                @else
                                    No subject assigned
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-question-circle mr-1"></i>
                            <span>{{ $note->questions_count }} questions</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-calendar mr-1"></i>
                            <span>{{ $note->created_at->format('M j, Y') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-1"></i>
                            <span>Updated {{ $note->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="ml-6 flex items-center space-x-2">
                    <a href="{{ route('teacher.notes.show', $note) }}" class="text-gray-400 hover:text-blue-600" title="View Note">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('teacher.notes.edit', $note) }}" class="text-gray-400 hover:text-blue-600" title="Edit Note">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="{{ route('teacher.questions.create') }}?note_id={{ $note->note_id }}" class="text-gray-400 hover:text-green-600" title="Generate Questions">
                        <i class="fas fa-question-circle"></i>
                    </a>
                    <button onclick="deleteNote('{{ $note->note_id }}')" class="text-gray-400 hover:text-red-600" title="Delete Note">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="mt-4 flex space-x-3">
                <a href="{{ route('teacher.notes.show', $note) }}" class="bg-primary text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition-colors">
                    <i class="fas fa-eye mr-1"></i>View
                </a>
                <a href="{{ route('teacher.notes.edit', $note) }}" class="bg-gray-100 text-gray-700 px-3 py-1 rounded text-sm hover:bg-gray-200 transition-colors">
                    <i class="fas fa-edit mr-1"></i>Edit
                </a>
                @if($note->status === 'draft')
                <form method="POST" action="{{ route('teacher.notes.update', $note) }}" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="title" value="{{ $note->title }}">
                    <input type="hidden" name="content" value="{{ $note->content }}">
                    <input type="hidden" name="status" value="published">
                    @foreach($note->subjects as $subject)
                    <input type="hidden" name="subjects[]" value="{{ $subject->subject_id }}">
                    @endforeach
                    <button type="submit" class="bg-green-100 text-green-700 px-3 py-1 rounded text-sm hover:bg-green-200 transition-colors">
                        <i class="fas fa-check mr-1"></i>Publish
                    </button>
                </form>
                @endif
                <a href="{{ route('teacher.questions.create') }}?note_id={{ $note->note_id }}" class="bg-purple-100 text-purple-700 px-3 py-1 rounded text-sm hover:bg-purple-200 transition-colors">
                    <i class="fas fa-question-circle mr-1"></i>Generate Questions
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($notes->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $notes->links() }}
    </div>
    @endif

    @else
    <div class="text-center py-12">
        <i class="fas fa-file-alt text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No content created yet</h3>
        <p class="text-gray-500 mb-6">Start creating study notes to help your students learn better.</p>
        <a href="{{ route('teacher.notes.create') }}" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-colors">
            <i class="fas fa-plus mr-2"></i>Create Your First Note
        </a>
    </div>
    @endif
</div>

<!-- Recent Activity -->
@if($notes->count() > 0)
<div class="mt-8 bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
    </div>
    <div class="p-6">
        <div class="space-y-4">
            @foreach($notes->take(5) as $note)
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-file-alt text-blue-600 text-sm"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm text-gray-900">
                        Updated <strong>{{ $note->title }}</strong>
                    </p>
                    <p class="text-xs text-gray-500">{{ $note->updated_at->diffForHumans() }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Hidden Delete Forms -->
@if($notes->count() > 0)
@foreach($notes as $note)
<form id="delete-note-form-{{ $note->note_id }}" action="{{ route('teacher.notes.delete', $note) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endforeach
@endif
@endsection

@section('scripts')
<script>
function deleteNote(noteId) {
    if (confirm('Are you sure you want to delete this note? This action cannot be undone and will also delete all associated questions.')) {
        document.getElementById('delete-note-form-' + noteId).submit();
    }
}

// Bulk selection functionality
let selectedNotes = [];

function toggleNoteSelection(noteId) {
    const checkbox = document.getElementById('note-' + noteId);
    if (checkbox.checked) {
        selectedNotes.push(noteId);
    } else {
        selectedNotes = selectedNotes.filter(id => id !== noteId);
    }

    updateBulkActions();
}

function selectAllNotes() {
    const checkboxes = document.querySelectorAll('input[name="selected_notes[]"]');
    const selectAllCheckbox = document.getElementById('select-all');

    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
        const noteId = checkbox.value;

        if (selectAllCheckbox.checked) {
            if (!selectedNotes.includes(noteId)) {
                selectedNotes.push(noteId);
            }
        } else {
            selectedNotes = selectedNotes.filter(id => id !== noteId);
        }
    });

    updateBulkActions();
}

function updateBulkActions() {
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');

    if (selectedNotes.length > 0) {
        bulkActions.style.display = 'block';
        selectedCount.textContent = selectedNotes.length;
    } else {
        bulkActions.style.display = 'none';
    }
}

function bulkDeleteNotes() {
    if (selectedNotes.length === 0) {
        alert('Please select notes to delete.');
        return;
    }

    if (confirm(`Are you sure you want to delete ${selectedNotes.length} selected notes? This action cannot be undone.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("teacher.notes.bulk-delete") }}';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        selectedNotes.forEach(noteId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'note_ids[]';
            input.value = noteId;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
