@extends('layouts.admin')

@section('title', 'Generated Questions')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Generated Questions</h1>
                    <p class="mt-1 text-sm text-gray-600">{{ $note->title }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('pdf-upload.download', $note->note_id) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-download mr-2"></i>Download PDF
                    </a>
                    <a href="{{ route('pdf-upload.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Upload Another
                    </a>
                    <a href="{{ route('pdf-upload.list') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-list mr-2"></i>View All
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-question-circle text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Questions Generated</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['questions_generated'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Correct Answers</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_answers'] }}</p>
                        <p class="text-xs text-green-600 mt-1">
                            @if($stats['questions_generated'] == $stats['total_answers'])
                                ✓ Perfect 1:1 Ratio (1 correct per question)
                            @else
                                {{ round($stats['total_answers'] / max(1, $stats['questions_generated']), 1) }}:1 Ratio
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-pdf text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">File Size</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['file_size'] / 1024, 1) }}KB</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Generated</p>
                        <p class="text-sm font-bold text-gray-900">{{ $note->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document Info -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Document Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Title</p>
                        <p class="text-sm text-gray-900">{{ $note->title }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Subject</p>
                        <p class="text-sm text-gray-900">{{ $note->subjects->first()->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Original Filename</p>
                        <p class="text-sm text-gray-900">{{ $note->file_name }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Questions List -->
        <div class="space-y-6">
            @foreach($note->questions as $index => $question)
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Question {{ $index + 1 }}
                            <span class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ ucfirst($question->difficulty) }}
                            </span>
                        </h3>
                        <div class="flex space-x-2">
                            <button onclick="copyQuestion({{ $index }})" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-gray-900 font-medium">{{ $question->question_text }}</p>
                    </div>

                    @if($question->answers->count() > 0)
                    <div class="space-y-2">
                        <p class="text-sm font-medium text-gray-700">Answers:</p>
                        @foreach($question->answers as $answerIndex => $answer)
                        <div class="flex items-center space-x-3 p-3 rounded-lg {{ $answer->is_correct ? 'bg-green-50 border border-green-200' : 'bg-gray-50' }}">
                            <span class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-sm font-medium {{ $answer->is_correct ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-600' }}">
                                {{ chr(65 + $answerIndex) }}
                            </span>
                            <span class="text-gray-900">{{ $answer->answer_text }}</span>
                            @if($answer->is_correct)
                            <span class="ml-auto">
                                <i class="fas fa-check text-green-600"></i>
                            </span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Actions -->
        <div class="mt-8 bg-white rounded-lg shadow">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('pdf-upload.download', $note->note_id) }}" 
                       class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-download mr-2"></i>Download Original PDF
                    </a>
                    <button onclick="exportQuestions()" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-file-export mr-2"></i>Export Questions
                    </button>
                    <button onclick="printQuestions()" 
                            class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fas fa-print mr-2"></i>Print Questions
                    </button>
                    <form action="{{ route('pdf-upload.delete', $note->note_id) }}" method="POST" class="inline"
                          onsubmit="return confirm('Are you sure you want to delete this PDF and all generated questions?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-2"></i>Delete All
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Copy question to clipboard
    function copyQuestion(index) {
        const questionElement = document.querySelectorAll('.bg-white.rounded-lg.shadow')[index + 2]; // +2 to skip stats and info cards
        const questionText = questionElement.querySelector('.text-gray-900.font-medium').textContent;
        const answers = Array.from(questionElement.querySelectorAll('.space-y-2 .flex.items-center')).map((el, i) => {
            return `${String.fromCharCode(65 + i)}. ${el.querySelector('.text-gray-900').textContent}`;
        }).join('\n');
        
        const fullText = `${questionText}\n\n${answers}`;
        
        navigator.clipboard.writeText(fullText).then(() => {
            // Show success message
            const button = event.target.closest('button');
            const icon = button.querySelector('i');
            icon.className = 'fas fa-check text-green-600';
            setTimeout(() => {
                icon.className = 'fas fa-copy';
            }, 2000);
        });
    }

    // Export questions
    function exportQuestions() {
        let exportText = `{{ $note->title }}\n`;
        exportText += `Generated: {{ $note->created_at->format('Y-m-d H:i:s') }}\n`;
        exportText += `Questions: {{ $stats['questions_generated'] }}\n\n`;
        exportText += '='.repeat(50) + '\n\n';

        @foreach($note->questions as $index => $question)
        exportText += `Question {{ $index + 1 }} ({{ strtoupper($question->difficulty) }})\n`;
        exportText += `{{ $question->question_text }}\n\n`;
        @foreach($question->answers as $answerIndex => $answer)
        exportText += `{{ chr(65 + $answerIndex) }}. {{ $answer->answer_text }}{{ $answer->is_correct ? ' ✓' : '' }}\n`;
        @endforeach
        exportText += '\n' + '-'.repeat(30) + '\n\n';
        @endforeach

        const blob = new Blob([exportText], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `{{ Str::slug($note->title) }}_questions.txt`;
        a.click();
        URL.revokeObjectURL(url);
    }

    // Print questions
    function printQuestions() {
        window.print();
    }
</script>

<style>
    @media print {
        .bg-gray-50, .shadow, .border-gray-200 {
            background: white !important;
            box-shadow: none !important;
            border: 1px solid #ccc !important;
        }
        
        .text-blue-600, .text-green-600, .text-purple-600, .text-yellow-600 {
            color: #333 !important;
        }
        
        .bg-blue-100, .bg-green-100, .bg-purple-100, .bg-yellow-100 {
            background: #f5f5f5 !important;
        }
        
        button, .bg-green-600, .bg-blue-600, .bg-purple-600, .bg-red-600 {
            display: none !important;
        }
    }
</style>
@endsection
