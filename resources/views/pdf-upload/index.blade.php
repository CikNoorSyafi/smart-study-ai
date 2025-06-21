@extends('layouts.admin')

@section('title', 'PDF Question Generator')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">PDF Question Generator</h1>
                    <p class="mt-1 text-sm text-gray-600">Upload a PDF and automatically generate questions and answers</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('pdf-upload.list') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-list mr-2"></i>View Uploads
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Upload Form -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <form action="{{ route('pdf-upload.upload') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    
                    <!-- File Upload Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Upload PDF Document</h3>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors" id="dropZone">
                            <div class="space-y-4">
                                <div class="mx-auto w-12 h-12 text-gray-400">
                                    <i class="fas fa-file-pdf text-4xl"></i>
                                </div>
                                <div>
                                    <label for="pdf_file" class="cursor-pointer">
                                        <span class="text-blue-600 hover:text-blue-500 font-medium">Click to upload</span>
                                        <span class="text-gray-500"> or drag and drop</span>
                                    </label>
                                    <input type="file" name="pdf_file" id="pdf_file" accept=".pdf" required class="hidden">
                                </div>
                                <p class="text-sm text-gray-500">PDF files up to 2MB</p>
                            </div>
                        </div>
                        @error('pdf_file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <!-- File Preview -->
                        <div id="filePreview" class="mt-4 hidden">
                            <div class="bg-gray-50 rounded-lg p-4 flex items-center space-x-3">
                                <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900" id="fileName"></p>
                                    <p class="text-sm text-gray-500" id="fileSize"></p>
                                </div>
                                <button type="button" onclick="removeFile()" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Document Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Document Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Document Title *</label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                       placeholder="Enter a descriptive title"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                                <select name="subject_id" id="subject_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('subject_id') border-red-500 @enderror">
                                    <option value="">Select Subject</option>
                                    @foreach($subjects as $subject)
                                    <option value="{{ $subject->subject_id }}" {{ old('subject_id') === $subject->subject_id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('subject_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Question Generation Settings -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Question Generation Settings</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="question_count" class="block text-sm font-medium text-gray-700 mb-2">Number of Questions *</label>
                                <input type="number" name="question_count" id="question_count" value="{{ old('question_count', 10) }}" 
                                       min="1" max="50" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('question_count') border-red-500 @enderror">
                                @error('question_count')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Generate 1-50 questions</p>
                            </div>

                            <div>
                                <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-2">Difficulty Level *</label>
                                <select name="difficulty" id="difficulty" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('difficulty') border-red-500 @enderror">
                                    <option value="easy" {{ old('difficulty') === 'easy' ? 'selected' : '' }}>Easy</option>
                                    <option value="medium" {{ old('difficulty', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="hard" {{ old('difficulty') === 'hard' ? 'selected' : '' }}>Hard</option>
                                </select>
                                @error('difficulty')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Question Types -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Question Types *</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="question_types[]" value="multiple_choice" id="multiple_choice" 
                                       {{ in_array('multiple_choice', old('question_types', ['multiple_choice'])) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="multiple_choice" class="ml-2 block text-sm text-gray-900">
                                    Multiple Choice
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="question_types[]" value="true_false" id="true_false"
                                       {{ in_array('true_false', old('question_types', [])) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="true_false" class="ml-2 block text-sm text-gray-900">
                                    True/False
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="question_types[]" value="short_answer" id="short_answer"
                                       {{ in_array('short_answer', old('question_types', [])) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="short_answer" class="ml-2 block text-sm text-gray-900">
                                    Short Answer
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="question_types[]" value="essay" id="essay"
                                       {{ in_array('essay', old('question_types', [])) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="essay" class="ml-2 block text-sm text-gray-900">
                                    Essay Questions
                                </label>
                            </div>
                        </div>
                        @error('question_types')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">Select at least one question type</p>
                    </div>

                    <!-- Question Generator Selection -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Question Generator *</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                                <div class="flex items-center mb-3">
                                    <input type="radio" name="generator_type" value="local" id="generator_local"
                                           {{ old('generator_type', 'local') === 'local' ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <label for="generator_local" class="ml-2 block text-sm font-medium text-gray-900">
                                        Local Generator
                                    </label>
                                    <span class="ml-auto bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Fast</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">Built-in question generator with pattern-based analysis</p>
                                <div class="text-xs text-gray-500">
                                    <div class="flex items-center mb-1">
                                        <i class="fas fa-check text-green-500 mr-1"></i>
                                        <span>Fast generation</span>
                                    </div>
                                    <div class="flex items-center mb-1">
                                        <i class="fas fa-check text-green-500 mr-1"></i>
                                        <span>No API costs</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-1"></i>
                                        <span>Offline capability</span>
                                    </div>
                                </div>
                            </div>

                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                                <div class="flex items-center mb-3">
                                    <input type="radio" name="generator_type" value="groq" id="generator_groq"
                                           {{ old('generator_type') === 'groq' ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <label for="generator_groq" class="ml-2 block text-sm font-medium text-gray-900">
                                        Groq AI Generator
                                    </label>
                                    <span class="ml-auto bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">AI Powered</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">Advanced AI-powered question generator using Groq's language models</p>
                                <div class="text-xs text-gray-500">
                                    <div class="flex items-center mb-1">
                                        <i class="fas fa-check text-green-500 mr-1"></i>
                                        <span>Advanced AI analysis</span>
                                    </div>
                                    <div class="flex items-center mb-1">
                                        <i class="fas fa-check text-green-500 mr-1"></i>
                                        <span>Natural language understanding</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-1"></i>
                                        <span>High-quality output</span>
                                    </div>
                                </div>
                                <div id="groq-status" class="mt-2 text-xs">
                                    <span class="text-gray-500">Checking API status...</span>
                                </div>
                            </div>
                        </div>
                        @error('generator_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Advanced Settings -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Advanced Settings</h3>
                            <button type="button" id="toggleAdvanced" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <i class="fas fa-chevron-down mr-1"></i>Show Advanced Options
                            </button>
                        </div>

                        <div id="advancedSettings" class="hidden space-y-6">
                            <!-- Question Generation Strategy -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Question Generation Strategy</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="strategies[]" value="concept_based" id="concept_based"
                                               {{ in_array('concept_based', old('strategies', ['concept_based'])) ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="concept_based" class="ml-2 block text-sm text-gray-900">
                                            Concept-Based Questions
                                            <span class="block text-xs text-gray-500">Focus on key concepts and definitions</span>
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="strategies[]" value="fact_based" id="fact_based"
                                               {{ in_array('fact_based', old('strategies', ['fact_based'])) ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="fact_based" class="ml-2 block text-sm text-gray-900">
                                            Fact-Based Questions
                                            <span class="block text-xs text-gray-500">Focus on specific facts and data</span>
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="strategies[]" value="relationship_based" id="relationship_based"
                                               {{ in_array('relationship_based', old('strategies', ['relationship_based'])) ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="relationship_based" class="ml-2 block text-sm text-gray-900">
                                            Relationship Questions
                                            <span class="block text-xs text-gray-500">Focus on connections between concepts</span>
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="strategies[]" value="application_based" id="application_based"
                                               {{ in_array('application_based', old('strategies', ['application_based'])) ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="application_based" class="ml-2 block text-sm text-gray-900">
                                            Application Questions
                                            <span class="block text-xs text-gray-500">Focus on practical applications</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Question Quality Settings -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="min_sentence_length" class="block text-sm font-medium text-gray-700 mb-2">Minimum Sentence Length</label>
                                    <input type="number" name="min_sentence_length" id="min_sentence_length" value="{{ old('min_sentence_length', 10) }}"
                                           min="5" max="50"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <p class="mt-1 text-sm text-gray-500">Minimum words in source sentences</p>
                                </div>

                                <div>
                                    <label for="concept_threshold" class="block text-sm font-medium text-gray-700 mb-2">Concept Frequency Threshold</label>
                                    <input type="number" name="concept_threshold" id="concept_threshold" value="{{ old('concept_threshold', 3) }}"
                                           min="1" max="10"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <p class="mt-1 text-sm text-gray-500">Minimum concept mentions for inclusion</p>
                                </div>
                            </div>

                            <!-- Enhanced Features -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Enhanced Features</label>
                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="enhanced_features[]" value="smart_distractors" id="smart_distractors"
                                               {{ in_array('smart_distractors', old('enhanced_features', ['smart_distractors'])) ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="smart_distractors" class="ml-2 block text-sm text-gray-900">
                                            Smart Distractors
                                            <span class="block text-xs text-gray-500">Generate more realistic wrong answers</span>
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="enhanced_features[]" value="context_analysis" id="context_analysis"
                                               {{ in_array('context_analysis', old('enhanced_features', ['context_analysis'])) ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="context_analysis" class="ml-2 block text-sm text-gray-900">
                                            Deep Context Analysis
                                            <span class="block text-xs text-gray-500">Analyze text structure and relationships</span>
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="enhanced_features[]" value="duplicate_detection" id="duplicate_detection"
                                               {{ in_array('duplicate_detection', old('enhanced_features', ['duplicate_detection'])) ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="duplicate_detection" class="ml-2 block text-sm text-gray-900">
                                            Advanced Duplicate Detection
                                            <span class="block text-xs text-gray-500">Prevent similar questions</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.dashboard') }}"
                           class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" id="submitBtn"
                                class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="submitText">
                                <i class="fas fa-magic mr-2"></i>Generate Questions
                            </span>
                            <span id="loadingText" class="hidden">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Processing...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Instructions -->
        <div class="mt-8 bg-blue-50 rounded-lg p-6">
            <h3 class="text-lg font-medium text-blue-900 mb-4">
                <i class="fas fa-info-circle mr-2"></i>Enhanced AI Question Generator
            </h3>
            <div class="space-y-2 text-sm text-blue-800">
                <p>• <strong>Smart Text Analysis:</strong> Advanced AI analyzes your PDF content for concepts, relationships, and key information</p>
                <p>• <strong>Multiple Question Strategies:</strong> Generates concept-based, fact-based, relationship, and application questions</p>
                <p>• <strong>Intelligent Distractors:</strong> Creates realistic wrong answers for multiple choice questions</p>
                <p>• <strong>Context-Aware Generation:</strong> Questions are generated based on actual content context and meaning</p>
                <p>• <strong>Quality Assurance:</strong> Advanced duplicate detection and quality filtering ensure reliable questions</p>
                <p>• <strong>Customizable Output:</strong> Control difficulty, question types, and generation strategies</p>
            </div>
        </div>

        <!-- Features Highlight -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-green-50 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <i class="fas fa-brain text-green-600 mr-2"></i>
                    <h4 class="font-medium text-green-900">Smart Analysis</h4>
                </div>
                <p class="text-sm text-green-800">AI identifies key concepts, relationships, and important facts automatically</p>
            </div>

            <div class="bg-purple-50 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <i class="fas fa-cogs text-purple-600 mr-2"></i>
                    <h4 class="font-medium text-purple-900">Multiple Strategies</h4>
                </div>
                <p class="text-sm text-purple-800">Uses 4 different question generation strategies for comprehensive coverage</p>
            </div>

            <div class="bg-orange-50 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <i class="fas fa-shield-alt text-orange-600 mr-2"></i>
                    <h4 class="font-medium text-orange-900">Quality Assured</h4>
                </div>
                <p class="text-sm text-orange-800">Advanced filtering prevents duplicates and ensures question reliability</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // File upload handling
    const fileInput = document.getElementById('pdf_file');
    const dropZone = document.getElementById('dropZone');
    const filePreview = document.getElementById('filePreview');
    const uploadForm = document.getElementById('uploadForm');
    const submitBtn = document.getElementById('submitBtn');

    // Drag and drop functionality
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-blue-400', 'bg-blue-50');
    });

    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-400', 'bg-blue-50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-400', 'bg-blue-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0 && files[0].type === 'application/pdf') {
            fileInput.files = files;
            showFilePreview(files[0]);
        }
    });

    // File input change
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            showFilePreview(e.target.files[0]);
        }
    });

    // Show file preview
    function showFilePreview(file) {
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = formatFileSize(file.size);
        filePreview.classList.remove('hidden');
        dropZone.classList.add('hidden');
    }

    // Remove file
    function removeFile() {
        fileInput.value = '';
        filePreview.classList.add('hidden');
        dropZone.classList.remove('hidden');
    }

    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Form submission
    uploadForm.addEventListener('submit', (e) => {
        submitBtn.disabled = true;
        document.getElementById('submitText').classList.add('hidden');
        document.getElementById('loadingText').classList.remove('hidden');
    });

    // Auto-fill title from filename
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0 && !document.getElementById('title').value) {
            const filename = e.target.files[0].name;
            const title = filename.replace(/\.[^/.]+$/, "").replace(/[-_]/g, ' ');
            document.getElementById('title').value = title;
        }
    });

    // Advanced settings toggle
    const toggleAdvanced = document.getElementById('toggleAdvanced');
    const advancedSettings = document.getElementById('advancedSettings');

    toggleAdvanced.addEventListener('click', () => {
        const isHidden = advancedSettings.classList.contains('hidden');

        if (isHidden) {
            advancedSettings.classList.remove('hidden');
            toggleAdvanced.innerHTML = '<i class="fas fa-chevron-up mr-1"></i>Hide Advanced Options';
        } else {
            advancedSettings.classList.add('hidden');
            toggleAdvanced.innerHTML = '<i class="fas fa-chevron-down mr-1"></i>Show Advanced Options';
        }
    });

    // Question type validation
    const questionTypeCheckboxes = document.querySelectorAll('input[name="question_types[]"]');
    const submitButton = document.getElementById('submitBtn');

    function validateQuestionTypes() {
        const checkedTypes = Array.from(questionTypeCheckboxes).some(cb => cb.checked);
        if (!checkedTypes) {
            questionTypeCheckboxes.forEach(cb => {
                cb.setCustomValidity('Please select at least one question type');
            });
        } else {
            questionTypeCheckboxes.forEach(cb => {
                cb.setCustomValidity('');
            });
        }
    }

    questionTypeCheckboxes.forEach(cb => {
        cb.addEventListener('change', validateQuestionTypes);
    });

    // Enhanced question count suggestions
    const questionCountInput = document.getElementById('question_count');
    const difficultySelect = document.getElementById('difficulty');

    function updateQuestionCountSuggestion() {
        const difficulty = difficultySelect.value;
        const currentCount = parseInt(questionCountInput.value) || 10;

        let suggestion = '';
        switch (difficulty) {
            case 'easy':
                suggestion = 'Recommended: 15-25 questions for easy difficulty';
                break;
            case 'medium':
                suggestion = 'Recommended: 10-20 questions for medium difficulty';
                break;
            case 'hard':
                suggestion = 'Recommended: 5-15 questions for hard difficulty';
                break;
        }

        const existingHint = questionCountInput.parentNode.querySelector('.difficulty-hint');
        if (existingHint) {
            existingHint.textContent = suggestion;
        } else {
            const hint = document.createElement('p');
            hint.className = 'mt-1 text-sm text-blue-600 difficulty-hint';
            hint.textContent = suggestion;
            questionCountInput.parentNode.appendChild(hint);
        }
    }

    difficultySelect.addEventListener('change', updateQuestionCountSuggestion);
    updateQuestionCountSuggestion(); // Initial call

    // Check Groq API status
    async function checkGroqStatus() {
        const statusElement = document.getElementById('groq-status');
        const groqRadio = document.getElementById('generator_groq');
        const groqContainer = groqRadio.parentElement.parentElement;

        try {
            // Check if CSRF token exists
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                throw new Error('CSRF token not found');
            }

            const response = await fetch('/api/question-generator/test', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ generator_type: 'groq' })
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const data = await response.json();

            if (data.success && data.results && data.results.groq && data.results.groq.success) {
                statusElement.innerHTML = '<span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>API Connected</span>';
                groqRadio.disabled = false;
                groqContainer.classList.remove('opacity-50');
            } else {
                const errorMsg = data.results?.groq?.message || 'API not available';
                statusElement.innerHTML = `<span class="text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>${errorMsg}</span>`;
                groqRadio.disabled = true;
                groqContainer.classList.add('opacity-50');
            }
        } catch (error) {
            console.error('Groq status check failed:', error);
            statusElement.innerHTML = '<span class="text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>Connection Error</span>';
            groqRadio.disabled = true;
            groqContainer.classList.add('opacity-50');

            // If Groq is disabled, ensure Local is selected
            document.getElementById('generator_local').checked = true;
        }
    }

    // Check status on page load
    checkGroqStatus();
</script>
@endsection
