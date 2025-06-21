<!-- AI Chat Widget -->
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center">
            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-robot text-blue-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">AI Study Assistant</h3>
                <p class="text-sm text-gray-500">Get help with your notes</p>
            </div>
        </div>
        <a href="{{ route('ai-chat.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-external-link-alt"></i>
        </a>
    </div>

    <!-- Quick Chat Interface -->
    <div id="chatWidget" class="space-y-4">
        <!-- Recent Notes -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Quick Note Access</label>
            <select id="quickNoteSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Select a note...</option>
                @foreach($recentNotes as $note)
                <option value="{{ $note->note_id }}">{{ Str::limit($note->title, 40) }}</option>
                @endforeach
            </select>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 gap-2 mb-4">
            <button id="quickSummaryBtn" class="p-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium disabled:opacity-50" disabled>
                <i class="fas fa-file-alt mr-1"></i>
                Get Summary
            </button>
            <button id="quickQuestionBtn" class="p-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors text-sm font-medium">
                <i class="fas fa-question-circle mr-1"></i>
                Ask Question
            </button>
        </div>

        <!-- Quick Question Input -->
        <div id="quickQuestionInput" class="hidden">
            <div class="flex space-x-2">
                <input type="text" 
                       id="quickQuestion" 
                       placeholder="Ask a quick question..." 
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <button id="sendQuickQuestion" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>

        <!-- Quick Response Area -->
        <div id="quickResponse" class="hidden">
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="flex items-start space-x-2">
                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mt-0.5">
                        <i class="fas fa-robot text-blue-600 text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <div id="quickResponseContent" class="text-sm text-gray-800"></div>
                        <div class="mt-2 text-xs text-gray-500">
                            <a href="{{ route('ai-chat.index') }}" class="text-blue-600 hover:text-blue-800">
                                Continue conversation in full chat →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div id="quickLoading" class="hidden">
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="flex items-center space-x-2">
                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-robot text-blue-600 text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <div class="typing-indicator">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Study Tips -->
        <div class="mt-4 p-3 bg-yellow-50 rounded-lg">
            <h4 class="text-sm font-medium text-yellow-800 mb-1">💡 Study Tip</h4>
            <p class="text-xs text-yellow-700">Ask specific questions about your notes to get better explanations and summaries!</p>
        </div>
    </div>
</div>

<style>
.typing-indicator {
    display: flex;
    align-items: center;
    gap: 2px;
}

.typing-indicator span {
    height: 4px;
    width: 4px;
    background-color: #9CA3AF;
    border-radius: 50%;
    display: inline-block;
    animation: typing 1.4s infinite ease-in-out;
}

.typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
.typing-indicator span:nth-child(2) { animation-delay: -0.16s; }

@keyframes typing {
    0%, 80%, 100% { transform: scale(0); opacity: 0.5; }
    40% { transform: scale(1); opacity: 1; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quickNoteSelect = document.getElementById('quickNoteSelect');
    const quickSummaryBtn = document.getElementById('quickSummaryBtn');
    const quickQuestionBtn = document.getElementById('quickQuestionBtn');
    const quickQuestionInput = document.getElementById('quickQuestionInput');
    const quickQuestion = document.getElementById('quickQuestion');
    const sendQuickQuestion = document.getElementById('sendQuickQuestion');
    const quickResponse = document.getElementById('quickResponse');
    const quickResponseContent = document.getElementById('quickResponseContent');
    const quickLoading = document.getElementById('quickLoading');

    // Enable/disable summary button based on note selection
    quickNoteSelect.addEventListener('change', function() {
        quickSummaryBtn.disabled = !this.value;
    });

    // Quick summary
    quickSummaryBtn.addEventListener('click', function() {
        const noteId = quickNoteSelect.value;
        if (!noteId) return;

        showLoading();
        
        fetch('/api/ai-chat/summary', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ note_id: noteId })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showResponse(data.summary);
            } else {
                showResponse(data.message || 'Sorry, I couldn\'t generate a summary.', true);
            }
        })
        .catch(error => {
            hideLoading();
            showResponse('Sorry, I encountered an error.', true);
        });
    });

    // Toggle question input
    quickQuestionBtn.addEventListener('click', function() {
        quickQuestionInput.classList.toggle('hidden');
        if (!quickQuestionInput.classList.contains('hidden')) {
            quickQuestion.focus();
        }
    });

    // Send quick question
    function sendQuestion() {
        const question = quickQuestion.value.trim();
        if (!question) return;

        const noteId = quickNoteSelect.value;
        
        showLoading();
        quickQuestion.value = '';
        
        fetch('/api/ai-chat/answer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                question: question,
                note_id: noteId || null
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showResponse(data.answer);
            } else {
                showResponse(data.message || 'Sorry, I couldn\'t answer that question.', true);
            }
        })
        .catch(error => {
            hideLoading();
            showResponse('Sorry, I encountered an error.', true);
        });
    }

    sendQuickQuestion.addEventListener('click', sendQuestion);
    
    quickQuestion.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendQuestion();
        }
    });

    function showLoading() {
        quickResponse.classList.add('hidden');
        quickLoading.classList.remove('hidden');
    }

    function hideLoading() {
        quickLoading.classList.add('hidden');
    }

    function showResponse(content, isError = false) {
        quickResponseContent.textContent = content;
        quickResponse.classList.remove('hidden');
        
        if (isError) {
            quickResponseContent.classList.add('text-red-600');
        } else {
            quickResponseContent.classList.remove('text-red-600');
        }
    }
});
</script>
