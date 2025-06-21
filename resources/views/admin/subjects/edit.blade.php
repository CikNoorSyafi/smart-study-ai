@extends('layouts.admin')

@section('title', 'Edit Subject - ' . $subject->name)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Subject</h1>
                    <p class="mt-1 text-sm text-gray-600">Update subject information and settings</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.subjects.show', $subject) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i>View Subject
                    </a>
                    <a href="{{ route('admin.subjects.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Subjects
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Edit Form -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Subject Information</h3>
                <p class="text-sm text-gray-600">Update the subject details below</p>
            </div>
            
            <form action="{{ route('admin.subjects.update', $subject) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Subject Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Subject Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $subject->name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                               placeholder="Enter subject name"
                               required>
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                  placeholder="Enter subject description">{{ old('description', $subject->description) }}</textarea>
                        @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Level -->
                    <div>
                        <label for="form_level" class="block text-sm font-medium text-gray-700 mb-2">
                            Form Level
                        </label>
                        <select name="form_level" 
                                id="form_level"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('form_level') border-red-500 @enderror">
                            <option value="">Select Form Level</option>
                            <option value="Form 4" {{ old('form_level', $subject->form_level) === 'Form 4' ? 'selected' : '' }}>Form 4</option>
                            <option value="Form 5" {{ old('form_level', $subject->form_level) === 'Form 5' ? 'selected' : '' }}>Form 5</option>
                        </select>
                        @error('form_level')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Subject Category
                        </label>
                        <select name="category" 
                                id="category"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category') border-red-500 @enderror">
                            <option value="">Select Category</option>
                            <option value="Core" {{ old('category', $subject->category) === 'Core' ? 'selected' : '' }}>Core (Compulsory)</option>
                            <option value="Science" {{ old('category', $subject->category) === 'Science' ? 'selected' : '' }}>Science Stream</option>
                            <option value="Arts" {{ old('category', $subject->category) === 'Arts' ? 'selected' : '' }}>Arts Stream</option>
                            <option value="Technical" {{ old('category', $subject->category) === 'Technical' ? 'selected' : '' }}>Technical/Vocational</option>
                        </select>
                        @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subject Code -->
                    <div>
                        <label for="subject_code" class="block text-sm font-medium text-gray-700 mb-2">
                            Subject Code
                        </label>
                        <input type="text" 
                               name="subject_code" 
                               id="subject_code" 
                               value="{{ old('subject_code', $subject->subject_code) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('subject_code') border-red-500 @enderror"
                               placeholder="e.g., BM, BI, MAT, FIZ"
                               style="font-family: monospace;">
                        @error('subject_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Malaysian subject code (e.g., BM for Bahasa Melayu, BI for English)</p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200 mt-6">
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.subjects.show', $subject) }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                            Cancel
                        </a>
                    </div>
                    <div class="flex space-x-3">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>Update Subject
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Current Subject Info -->
        <div class="mt-8 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Current Subject Information</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Current Details</h4>
                        <div class="space-y-2 text-sm">
                            <div><span class="font-medium">Name:</span> {{ $subject->name }}</div>
                            <div><span class="font-medium">Form Level:</span> {{ $subject->form_level ?: 'Not specified' }}</div>
                            <div><span class="font-medium">Category:</span> {{ $subject->category ?: 'Not specified' }}</div>
                            <div><span class="font-medium">Subject Code:</span> <code>{{ $subject->subject_code ?: 'Not specified' }}</code></div>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Usage Statistics</h4>
                        <div class="space-y-2 text-sm">
                            <div><span class="font-medium">Enrolled Users:</span> {{ $subject->users->count() }}</div>
                            <div><span class="font-medium">Associated Notes:</span> {{ $subject->notes->count() }}</div>
                            <div><span class="font-medium">Teachers:</span> {{ $subject->teachers()->count() }}</div>
                            <div><span class="font-medium">Students:</span> {{ $subject->students()->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Malaysian Education Guidelines -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="px-6 py-4">
                <h3 class="text-lg font-semibold text-blue-900 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>Malaysian Education Guidelines
                </h3>
                <div class="text-sm text-blue-800 space-y-2">
                    <p><strong>Form Levels:</strong> Form 4 and Form 5 represent the final two years of Malaysian secondary education leading to SPM examinations.</p>
                    <p><strong>Subject Categories:</strong></p>
                    <ul class="list-disc list-inside ml-4 space-y-1">
                        <li><strong>Core:</strong> Compulsory subjects for all students (Bahasa Melayu, English, Mathematics, History, Moral/Islamic Studies)</li>
                        <li><strong>Science:</strong> For science stream students (Physics, Chemistry, Biology, Additional Mathematics)</li>
                        <li><strong>Arts:</strong> For arts stream students (Geography, Economics, Accounting, Business Studies)</li>
                        <li><strong>Technical:</strong> Vocational and technical subjects (ICT, Engineering, etc.)</li>
                    </ul>
                    <p><strong>Subject Codes:</strong> Use official Malaysian subject codes (BM, BI, MAT, SEJ, FIZ, KIM, BIO, etc.)</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-generate subject code based on name (optional helper)
    document.getElementById('name').addEventListener('input', function() {
        const name = this.value;
        const codeField = document.getElementById('subject_code');
        
        // Only auto-generate if code field is empty
        if (!codeField.value) {
            let code = '';
            if (name.toLowerCase().includes('bahasa melayu')) code = 'BM';
            else if (name.toLowerCase().includes('english')) code = 'BI';
            else if (name.toLowerCase().includes('mathematics') && !name.toLowerCase().includes('additional')) code = 'MAT';
            else if (name.toLowerCase().includes('additional mathematics')) code = 'MAT-T';
            else if (name.toLowerCase().includes('history')) code = 'SEJ';
            else if (name.toLowerCase().includes('physics')) code = 'FIZ';
            else if (name.toLowerCase().includes('chemistry')) code = 'KIM';
            else if (name.toLowerCase().includes('biology')) code = 'BIO';
            else if (name.toLowerCase().includes('geography')) code = 'GEO';
            else if (name.toLowerCase().includes('economics')) code = 'EKO';
            else if (name.toLowerCase().includes('accounting')) code = 'PEK';
            else if (name.toLowerCase().includes('business')) code = 'PN';
            else if (name.toLowerCase().includes('information') || name.toLowerCase().includes('ict')) code = 'ICT';
            else if (name.toLowerCase().includes('moral')) code = 'PM';
            else if (name.toLowerCase().includes('islamic')) code = 'PI';
            
            if (code) {
                codeField.value = code;
            }
        }
    });
</script>
@endsection
