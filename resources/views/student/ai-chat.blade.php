@extends('layouts.app')

@section('title', 'AI Study Assistant - Smart Study')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">AI Study Assistant</h1>
                    <p class="mt-1 text-sm text-gray-600">Get summaries and ask questions about your notes</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        @if(!$isAvailable)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                <span class="text-yellow-800">AI Chat is not available. Please contact your administrator.</span>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Notes Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Notes</h3>
                    
                    <div class="mb-4">
                        <button id="generalChatBtn" class="w-full text-left p-3 rounded-lg border-2 border-blue-200 bg-blue-50 hover:bg-blue-100 transition-colors">
                            <i class="fas fa-comments text-blue-600 mr-2"></i>
                            <span class="font-medium text-blue-900">General Study Help</span>
                        </button>
                    </div>

                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        @forelse($notes as $note)
                        <button class="note-item w-full text-left p-3 rounded-lg border hover:bg-gray-50 transition-colors" 
                                data-note-id="{{ $note->note_id }}" 
                                data-note-title="{{ $note->title }}">
                            <div class="font-medium text-gray-900 truncate">{{ $note->title }}</div>
                            <div class="text-sm text-gray-500">{{ $note->created_at->format('M j, Y') }}</div>
                        </button>
                        @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-sticky-note text-3xl mb-2"></i>
                            <p>No notes found</p>
                            <p class="text-sm">Create some notes to get started!</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Chat Interface -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow h-[600px] flex flex-col">
                    <!-- Chat Header -->
                    <div class="border-b p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 id="chatTitle" class="font-semibold text-gray-900">AI Study Assistant</h3>
                                <p id="chatSubtitle" class="text-sm text-gray-500">Select a note or ask general study questions</p>
                            </div>
                            <button id="clearChatBtn" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Chat Messages -->
                    <div id="chatMessages" class="flex-1 overflow-y-auto p-4 space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-robot text-blue-600 text-sm"></i>
                            </div>
                            <div class="bg-gray-100 rounded-lg p-3 max-w-md">
                                <p class="text-gray-800">Hello! I'm your AI study assistant. I can help you:</p>
                                <ul class="mt-2 text-sm text-gray-600 space-y-1">
                                    <li>• Summarize your notes</li>
                                    <li>• Answer questions about your notes</li>
                                    <li>• Provide general study help</li>
                                </ul>
                                <p class="mt-2 text-sm text-gray-600">Select a note from the sidebar or ask me anything!</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div id="quickActions" class="border-t p-4 bg-gray-50">
                        <div class="flex flex-wrap gap-2">
                            <button class="quick-action-btn px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm hover:bg-blue-200 transition-colors">
                                Summarize this note
                            </button>
                            <button class="quick-action-btn px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm hover:bg-green-200 transition-colors">
                                Key concepts
                            </button>
                            <button class="quick-action-btn px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm hover:bg-purple-200 transition-colors">
                                Practice questions
                            </button>
                        </div>
                    </div>

                    <!-- Chat Input -->
                    <div class="border-t p-4">
                        <form id="chatForm" class="flex space-x-2">
                            <input type="text" 
                                   id="messageInput" 
                                   placeholder="Ask a question about your notes..." 
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   {{ !$isAvailable ? 'disabled' : '' }}>
                            <button type="submit" 
                                    id="sendBtn"
                                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors disabled:bg-gray-400"
                                    {{ !$isAvailable ? 'disabled' : '' }}>
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.note-item.active {
    border-color: #3B82F6;
    background-color: #EFF6FF;
}

.typing-indicator {
    display: flex;
    align-items: center;
    space-x-1;
}

.typing-indicator span {
    height: 8px;
    width: 8px;
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
    const chatMessages = document.getElementById('chatMessages');
    const messageInput = document.getElementById('messageInput');
    const chatForm = document.getElementById('chatForm');
    const chatTitle = document.getElementById('chatTitle');
    const chatSubtitle = document.getElementById('chatSubtitle');
    const clearChatBtn = document.getElementById('clearChatBtn');
    const generalChatBtn = document.getElementById('generalChatBtn');
    const quickActionBtns = document.querySelectorAll('.quick-action-btn');
    const noteItems = document.querySelectorAll('.note-item');
    
    let currentNoteId = null;
    let currentNoteTitle = null;

    // Note selection
    noteItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all items
            noteItems.forEach(i => i.classList.remove('active'));
            generalChatBtn.classList.remove('border-blue-500', 'bg-blue-100');
            generalChatBtn.classList.add('border-blue-200', 'bg-blue-50');
            
            // Add active class to clicked item
            this.classList.add('active');
            
            currentNoteId = this.dataset.noteId;
            currentNoteTitle = this.dataset.noteTitle;
            
            chatTitle.textContent = currentNoteTitle;
            chatSubtitle.textContent = 'Ask questions about this note';
            messageInput.placeholder = `Ask a question about "${currentNoteTitle}"...`;
        });
    });

    // General chat selection
    generalChatBtn.addEventListener('click', function() {
        // Remove active class from all note items
        noteItems.forEach(i => i.classList.remove('active'));
        
        // Activate general chat
        this.classList.remove('border-blue-200', 'bg-blue-50');
        this.classList.add('border-blue-500', 'bg-blue-100');
        
        currentNoteId = null;
        currentNoteTitle = null;
        
        chatTitle.textContent = 'AI Study Assistant';
        chatSubtitle.textContent = 'General study help and guidance';
        messageInput.placeholder = 'Ask any study-related question...';
    });

    // Quick actions
    quickActionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const action = this.textContent.trim();
            if (currentNoteId) {
                if (action === 'Summarize this note') {
                    getSummary();
                } else {
                    sendMessage(`${action} for this note`);
                }
            } else {
                sendMessage(action);
            }
        });
    });

    // Chat form submission
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = messageInput.value.trim();
        if (message) {
            sendMessage(message);
            messageInput.value = '';
        }
    });

    // Clear chat
    clearChatBtn.addEventListener('click', function() {
        const messages = chatMessages.querySelectorAll('.message');
        messages.forEach(msg => msg.remove());
    });

    function sendMessage(message) {
        // Add user message
        addMessage(message, 'user');
        
        // Show typing indicator
        showTypingIndicator();
        
        // Send to API
        fetch('/api/ai-chat/answer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                question: message,
                note_id: currentNoteId
            })
        })
        .then(response => response.json())
        .then(data => {
            hideTypingIndicator();
            if (data.success) {
                addMessage(data.answer, 'ai');
            } else {
                addMessage(data.message || 'Sorry, I encountered an error. Please try again.', 'ai', true);
            }
        })
        .catch(error => {
            hideTypingIndicator();
            addMessage('Sorry, I encountered an error. Please try again.', 'ai', true);
        });
    }

    function getSummary() {
        if (!currentNoteId) return;
        
        // Show typing indicator
        showTypingIndicator();
        
        fetch('/api/ai-chat/summary', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                note_id: currentNoteId
            })
        })
        .then(response => response.json())
        .then(data => {
            hideTypingIndicator();
            if (data.success) {
                addMessage(`Here's a summary of "${data.note_title}":`, 'ai');
                addMessage(data.summary, 'ai');
            } else {
                addMessage(data.message || 'Sorry, I couldn\'t generate a summary.', 'ai', true);
            }
        })
        .catch(error => {
            hideTypingIndicator();
            addMessage('Sorry, I encountered an error generating the summary.', 'ai', true);
        });
    }

    function addMessage(content, sender, isError = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'message flex items-start space-x-3';
        
        const isUser = sender === 'user';
        const iconClass = isUser ? 'fas fa-user' : 'fas fa-robot';
        const iconBg = isUser ? 'bg-green-100' : (isError ? 'bg-red-100' : 'bg-blue-100');
        const iconColor = isUser ? 'text-green-600' : (isError ? 'text-red-600' : 'text-blue-600');
        const messageBg = isUser ? 'bg-green-100' : (isError ? 'bg-red-100' : 'bg-gray-100');
        
        messageDiv.innerHTML = `
            <div class="w-8 h-8 ${iconBg} rounded-full flex items-center justify-center">
                <i class="${iconClass} ${iconColor} text-sm"></i>
            </div>
            <div class="${messageBg} rounded-lg p-3 max-w-md">
                <p class="text-gray-800 whitespace-pre-wrap">${content}</p>
            </div>
        `;
        
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function showTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.id = 'typingIndicator';
        typingDiv.className = 'message flex items-start space-x-3';
        typingDiv.innerHTML = `
            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-robot text-blue-600 text-sm"></i>
            </div>
            <div class="bg-gray-100 rounded-lg p-3">
                <div class="typing-indicator">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        `;
        
        chatMessages.appendChild(typingDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function hideTypingIndicator() {
        const typingIndicator = document.getElementById('typingIndicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }
});
</script>
@endsection
