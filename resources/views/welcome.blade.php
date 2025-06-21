@extends('layouts.app')

@section('title', 'Smart Study - Transform Learning into Questions')

@section('content')
    <!-- Hero Section -->
    <section class="bg-white py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 mb-4 sm:mb-6 leading-tight">
                    Transform Learning into<br class="hidden sm:block">Interactive Questions
                </h1>
                <p class="text-lg sm:text-xl text-slate-600 mb-6 sm:mb-8 max-w-3xl mx-auto px-4 sm:px-0">
                    Create engaging questions from any learning material in seconds. Boost retention, test understanding, and make learning more effective.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center max-w-md sm:max-w-none mx-auto">
                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 sm:px-8 py-3 sm:py-3.5 rounded-lg text-base sm:text-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-sm hover:shadow-md transform hover:scale-[1.02] active:scale-[0.98]">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Try It Free
                        </span>
                    </a>
                    <a href="#how-it-works" class="border border-slate-300 text-slate-700 px-6 sm:px-8 py-3 sm:py-3.5 rounded-lg text-base sm:text-lg font-semibold hover:bg-slate-50 hover:border-slate-400 transition-all duration-200">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            See How It Works
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Question Examples -->
    <section class="bg-gray-50 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Multiple Choice Question -->
                <div class="bg-white p-6 rounded-lg shadow-sm border">
                    <h3 class="font-semibold text-gray-900 mb-4">What is the primary function of mitochondria in a cell?</h3>
                    <div class="space-y-3">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="radio" name="q1" class="text-blue-600">
                            <span class="bg-blue-600 text-white px-3 py-1 rounded text-sm">Energy production</span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="radio" name="q1" class="text-blue-600">
                            <span class="text-gray-700">Protein synthesis</span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="radio" name="q1" class="text-blue-600">
                            <span class="text-gray-700">Cell division</span>
                        </label>
                    </div>
                </div>

                <!-- Short Answer Question -->
                <div class="bg-white p-6 rounded-lg shadow-sm border">
                    <h3 class="font-semibold text-gray-900 mb-4">Explain how photosynthesis converts light energy into chemical energy.</h3>
                    <textarea class="w-full p-3 border border-gray-300 rounded-lg resize-none" rows="4" placeholder="Type your answer here..."></textarea>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="bg-white py-12 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-8 text-center">
                <div class="bg-slate-50 rounded-xl p-6 sm:p-8">
                    <div class="text-3xl sm:text-4xl font-bold text-slate-900 mb-2">2.5M+</div>
                    <div class="text-slate-600 text-sm sm:text-base">Learning Materials Processed</div>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 sm:p-8">
                    <div class="text-3xl sm:text-4xl font-bold text-slate-900 mb-2">500K+</div>
                    <div class="text-slate-600 text-sm sm:text-base">Active Users</div>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 sm:p-8">
                    <div class="text-3xl sm:text-4xl font-bold text-slate-900 mb-2">10M+</div>
                    <div class="text-slate-600 text-sm sm:text-base">Questions Generated</div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="bg-slate-50 py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 mb-4">How It Works</h2>
                <p class="text-lg sm:text-xl text-slate-600 max-w-3xl mx-auto px-4 sm:px-0">
                    Transform your learning materials into engaging questions in just three simple steps.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
                <!-- Step 1 -->
                <div class="text-center bg-white rounded-xl p-6 sm:p-8 shadow-sm border border-slate-200/60 hover:shadow-md transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6 shadow-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-4">Upload Your Content</h3>
                    <p class="text-slate-600">Upload documents, paste text, or provide URLs. We support PDFs, Word docs, presentations, and more.</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center bg-white rounded-xl p-6 sm:p-8 shadow-sm border border-slate-200/60 hover:shadow-md transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6 shadow-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-4">AI Generates Questions</h3>
                    <p class="text-slate-600">Our AI analyzes your content and creates relevant questions based on key concepts and learning objectives.</p>
                </div>

                <!-- Step 3 -->
                <div class="text-center bg-white rounded-xl p-6 sm:p-8 shadow-sm border border-slate-200/60 hover:shadow-md transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6 shadow-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-4">Review & Export</h3>
                    <p class="text-slate-600">Review, edit, and customize your questions. Export to your preferred format or learning platform.</p>
                </div>
            </div>
        </div>
    </section>
@endsection