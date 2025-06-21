@extends('teacher.layout')

@section('title', 'Create Study Note')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Create Study Note</h1>
        <p class="mt-1 text-sm text-gray-600">Create a new study note for your students.</p>
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
    <form action="{{ route('teacher.notes.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Note Details</h3>
            
            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Title <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title') }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('title') border-red-500 @enderror"
                       placeholder="Enter note title..."
                       required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    Content <span class="text-red-500">*</span>
                </label>
                <textarea id="content" 
                          name="content" 
                          rows="12"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('content') border-red-500 @enderror"
                          placeholder="Write your study note content here..."
                          required>{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">You can use markdown formatting for better presentation.</p>
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select id="status" 
                        name="status" 
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('status') border-red-500 @enderror"
                        required>
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Draft notes are only visible to you. Published notes are visible to students.</p>
            </div>

            <!-- Subject Assignment -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Assign to Subjects
                </label>
                <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-md p-3">
                    @if($subjects->count() > 0)
                        @foreach($subjects as $subject)
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="subjects[]" 
                                   value="{{ $subject->subject_id }}"
                                   class="rounded border-gray-300 text-primary focus:ring-primary"
                                   {{ in_array($subject->subject_id, old('subjects', [])) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">{{ $subject->name }}</span>
                            @if($subject->description)
                            <span class="ml-2 text-xs text-gray-500">({{ Str::limit($subject->description, 50) }})</span>
                            @endif
                        </label>
                        @endforeach
                    @else
                        <p class="text-sm text-gray-500">No subjects assigned to you yet.</p>
                    @endif
                </div>
                @error('subjects')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Select which subjects this note should be available for.</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center">
                <div class="flex space-x-3">
                    <button type="submit" 
                            class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                        <i class="fas fa-save mr-2"></i>Create Note
                    </button>
                    <button type="button" 
                            onclick="saveDraft()"
                            class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-file-alt mr-2"></i>Save as Draft
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
@endsection

@section('scripts')
<script>
function saveDraft() {
    // Set status to draft and submit
    document.getElementById('status').value = 'draft';
    document.querySelector('form').submit();
}

// Auto-save functionality
let autoSaveTimer;
const titleInput = document.getElementById('title');
const contentTextarea = document.getElementById('content');

function autoSave() {
    const title = titleInput.value;
    const content = contentTextarea.value;
    
    if (title.trim() || content.trim()) {
        // Store in localStorage for recovery
        localStorage.setItem('note_draft', JSON.stringify({
            title: title,
            content: content,
            timestamp: new Date().toISOString()
        }));
        
        // Show auto-save indicator
        showAutoSaveIndicator();
    }
}

function showAutoSaveIndicator() {
    // Create or update auto-save indicator
    let indicator = document.getElementById('autosave-indicator');
    if (!indicator) {
        indicator = document.createElement('div');
        indicator.id = 'autosave-indicator';
        indicator.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-3 py-1 rounded text-sm';
        document.body.appendChild(indicator);
    }
    
    indicator.textContent = 'Auto-saved at ' + new Date().toLocaleTimeString();
    indicator.style.display = 'block';
    
    // Hide after 3 seconds
    setTimeout(() => {
        indicator.style.display = 'none';
    }, 3000);
}

// Set up auto-save
titleInput.addEventListener('input', () => {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(autoSave, 2000);
});

contentTextarea.addEventListener('input', () => {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(autoSave, 2000);
});

// Restore from localStorage on page load
window.addEventListener('load', () => {
    const draft = localStorage.getItem('note_draft');
    if (draft) {
        const data = JSON.parse(draft);
        const timeDiff = new Date() - new Date(data.timestamp);
        
        // If draft is less than 1 hour old, offer to restore
        if (timeDiff < 3600000 && (data.title || data.content)) {
            if (confirm('Found an auto-saved draft. Would you like to restore it?')) {
                titleInput.value = data.title || '';
                contentTextarea.value = data.content || '';
            }
        }
    }
});

// Clear draft when form is submitted successfully
document.querySelector('form').addEventListener('submit', () => {
    localStorage.removeItem('note_draft');
});
</script>
@endsection
