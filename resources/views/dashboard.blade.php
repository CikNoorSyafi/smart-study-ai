@extends('layouts.app')

@section('title', 'Dashboard - Smart Study')

@section('content')
    <div class="bg-slate-50 min-h-screen py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Welcome Header -->
            <div class="mb-6 sm:mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-sm">
                            <span class="text-white text-lg font-bold">{{ substr(session('user.name'), 0, 1) }}</span>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Welcome back, {{ session('user.name') }}!</h1>
                            @if(session('user.role') === 'student')
                                <p class="text-slate-600 mt-1">Ready to learn and practice with AI assistance today?</p>
                            @elseif(session('user.role') === 'parent')
                                <p class="text-slate-600 mt-1">Check your children's progress and stay connected.</p>
                            @elseif(session('user.role') === 'teacher')
                                <p class="text-slate-600 mt-1">Ready to create some amazing questions today?</p>
                            @else
                                <p class="text-slate-600 mt-1">Manage your Smart Study platform efficiently.</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-6">
                        <div class="flex space-x-4 sm:space-x-6">
                            @if(session('user.role') === 'student')
                                <div class="text-center bg-white rounded-lg p-4 border border-slate-200 shadow-sm">
                                    <div class="text-2xl font-bold text-green-600">{{ $recentNotes->count() ?? 0 }}</div>
                                    <div class="text-sm text-slate-500">Notes Created</div>
                                </div>
                                <div class="text-center bg-white rounded-lg p-4 border border-slate-200 shadow-sm">
                                    <div class="text-2xl font-bold text-blue-600">0</div>
                                    <div class="text-sm text-slate-500">AI Interactions</div>
                                </div>
                            @elseif(session('user.role') === 'parent')
                                <div class="text-center bg-white rounded-lg p-4 border border-slate-200 shadow-sm">
                                    <div class="text-2xl font-bold text-orange-600">2</div>
                                    <div class="text-sm text-slate-500">Children</div>
                                </div>
                                <div class="text-center bg-white rounded-lg p-4 border border-slate-200 shadow-sm">
                                    <div class="text-2xl font-bold text-yellow-600">4</div>
                                    <div class="text-sm text-slate-500">Recent Reports</div>
                                </div>
                            @else
                                <div class="text-center bg-white rounded-lg p-4 border border-slate-200 shadow-sm">
                                    <div class="text-2xl font-bold text-blue-600">0</div>
                                    <div class="text-sm text-slate-500">Questions Created</div>
                                </div>
                                <div class="text-center bg-white rounded-lg p-4 border border-slate-200 shadow-sm">
                                    <div class="text-2xl font-bold text-emerald-600">0</div>
                                    <div class="text-sm text-slate-500">Documents Processed</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
                @if(session('user.role') === 'admin' || session('user.role') === 'teacher')
                <!-- Create Questions (Admin/Teacher Only) -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-4 sm:p-6 hover:shadow-md transition-all duration-200 hover:border-blue-300">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 ml-3">Create Questions</h3>
                    </div>
                    <p class="text-slate-600 mb-4 text-sm sm:text-base">Upload your content and let AI generate questions automatically.</p>
                    <a href="{{ route('pdf-upload.index') }}" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-2.5 px-4 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 font-medium text-sm sm:text-base transform hover:scale-[1.02] active:scale-[0.98] block text-center">
                        Get Started
                    </a>
                </div>
                @elseif(session('user.role') === 'student')
                <!-- AI Study Assistant for Students -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-4 sm:p-6 hover:shadow-md transition-all duration-200 hover:border-green-300">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 ml-3">AI Study Assistant</h3>
                    </div>
                    <p class="text-slate-600 mb-4 text-sm sm:text-base">Get AI help with your notes, summaries, and study questions.</p>
                    <a href="{{ route('ai-chat.index') }}" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white py-2.5 px-4 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-medium text-sm sm:text-base transform hover:scale-[1.02] active:scale-[0.98] block text-center">
                        Start Chatting
                    </a>
                </div>
                @elseif(session('user.role') === 'parent')
                <!-- Student Progress for Parents -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-4 sm:p-6 hover:shadow-md transition-all duration-200 hover:border-orange-300">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 ml-3">My Children</h3>
                    </div>
                    <p class="text-slate-600 mb-4 text-sm sm:text-base">View your children's academic progress and performance.</p>
                    <a href="{{ route('parent.children') }}" class="w-full bg-gradient-to-r from-orange-600 to-red-600 text-white py-2.5 px-4 rounded-lg hover:from-orange-700 hover:to-red-700 transition-all duration-200 font-medium text-sm sm:text-base transform hover:scale-[1.02] active:scale-[0.98] block text-center">
                        View Progress
                    </a>
                </div>
                @endif

                @if(session('user.role') === 'student')
                <!-- Practice Questions for Students -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-4 sm:p-6 hover:shadow-md transition-all duration-200 hover:border-blue-300">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 ml-3">Practice Questions</h3>
                    </div>
                    <p class="text-slate-600 mb-4 text-sm sm:text-base">Practice with questions created by your teachers.</p>
                    <a href="{{ route('questions.index') }}" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-2.5 px-4 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 font-medium text-sm sm:text-base transform hover:scale-[1.02] active:scale-[0.98] block text-center">
                        Start Practice
                    </a>
                </div>
                @elseif(session('user.role') === 'admin' || session('user.role') === 'teacher')
                <!-- Question Bank for Admin/Teacher -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-4 sm:p-6 hover:shadow-md transition-all duration-200 hover:border-emerald-300">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 ml-3">Question Bank</h3>
                    </div>
                    <p class="text-slate-600 mb-4 text-sm sm:text-base">Browse and manage your saved questions and collections.</p>
                    <a href="{{ route('admin.questions.index') }}" class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 text-white py-2.5 px-4 rounded-lg hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 font-medium text-sm sm:text-base transform hover:scale-[1.02] active:scale-[0.98] block text-center">
                        View Bank
                    </a>
                </div>
                @elseif(session('user.role') === 'parent')
                <!-- Student Performance for Parents -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-4 sm:p-6 hover:shadow-md transition-all duration-200 hover:border-yellow-300">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 ml-3">Performance Reports</h3>
                    </div>
                    <p class="text-slate-600 mb-4 text-sm sm:text-base">View detailed performance reports and analytics for your children.</p>
                    <a href="{{ route('parent.reports') }}" class="w-full bg-gradient-to-r from-yellow-600 to-orange-600 text-white py-2.5 px-4 rounded-lg hover:from-yellow-700 hover:to-orange-700 transition-all duration-200 font-medium text-sm sm:text-base transform hover:scale-[1.02] active:scale-[0.98] block text-center">
                        View Reports
                    </a>
                </div>
                @endif

                @if(session('user.role') === 'student')
                <!-- Study Progress for Students -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-4 sm:p-6 hover:shadow-md transition-all duration-200 hover:border-purple-300 sm:col-span-2 lg:col-span-1">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 ml-3">My Progress</h3>
                    </div>
                    <p class="text-slate-600 mb-4 text-sm sm:text-base">Track your learning progress and achievements.</p>
                    <button class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-2.5 px-4 rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-200 font-medium text-sm sm:text-base transform hover:scale-[1.02] active:scale-[0.98]">
                        View Progress
                    </button>
                </div>
                @elseif(session('user.role') === 'parent')
                <!-- Communication for Parents -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-4 sm:p-6 hover:shadow-md transition-all duration-200 hover:border-green-300 sm:col-span-2 lg:col-span-1">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-green-500 to-teal-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 ml-3">Communication</h3>
                    </div>
                    <p class="text-slate-600 mb-4 text-sm sm:text-base">Communicate with teachers and school administration.</p>
                    <a href="{{ route('parent.messages') }}" class="w-full bg-gradient-to-r from-green-600 to-teal-600 text-white py-2.5 px-4 rounded-lg hover:from-green-700 hover:to-teal-700 transition-all duration-200 font-medium text-sm sm:text-base transform hover:scale-[1.02] active:scale-[0.98] block text-center">
                        Messages
                    </a>
                </div>
                @else
                <!-- Analytics for Admin/Teacher -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-4 sm:p-6 hover:shadow-md transition-all duration-200 hover:border-purple-300 sm:col-span-2 lg:col-span-1">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 ml-3">Analytics</h3>
                    </div>
                    <p class="text-slate-600 mb-4 text-sm sm:text-base">Track performance and insights from your assessments.</p>
                    <a href="{{ route('admin.analytics') }}" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-2.5 px-4 rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-200 font-medium text-sm sm:text-base transform hover:scale-[1.02] active:scale-[0.98] block text-center">
                        View Analytics
                    </a>
                </div>
                @endif
            </div>

            @if(session('user.role') === 'student')
            <!-- AI Study Assistant Section for Students -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 sm:mb-8">
                <div class="lg:col-span-2">
                    @include('components.ai-chat-widget', ['recentNotes' => $recentNotes ?? collect()])
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Study Progress</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Notes Created</span>
                            <span class="font-semibold text-gray-900">{{ $recentNotes->count() ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">AI Interactions</span>
                            <span class="font-semibold text-gray-900">0</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Study Sessions</span>
                            <span class="font-semibold text-gray-900">0</span>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t">
                        <a href="{{ route('ai-chat.index') }}" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors text-center block">
                            Open Full AI Chat
                        </a>
                    </div>
                </div>
            </div>
            @elseif(session('user.role') === 'parent')
            <!-- Parent Dashboard Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6 sm:mb-8">
                <!-- My Children -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">My Children</h3>
                    <div class="space-y-4">
                        <!-- Sample children data - in real app, this would come from database -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-blue-600 font-semibold">JS</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">John Smith Jr.</p>
                                    <p class="text-sm text-gray-500">Grade 8 • Math, Science</p>
                                </div>
                            </div>
                            <a href="{{ route('parent.child.progress', 1) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View Progress</a>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-pink-600 font-semibold">ES</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Emma Smith</p>
                                    <p class="text-sm text-gray-500">Grade 6 • English, History</p>
                                </div>
                            </div>
                            <a href="{{ route('parent.child.progress', 2) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View Progress</a>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t">
                        <a href="{{ route('parent.manage-children') }}" class="w-full bg-orange-600 text-white py-2 px-4 rounded-lg hover:bg-orange-700 transition-colors text-center block">
                            Manage Children
                        </a>
                    </div>
                </div>

                <!-- Recent Performance -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Performance</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">John's Math Quiz</span>
                            <span class="font-semibold text-green-600">85%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Emma's English Essay</span>
                            <span class="font-semibold text-blue-600">92%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">John's Science Test</span>
                            <span class="font-semibold text-yellow-600">78%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Emma's History Project</span>
                            <span class="font-semibold text-green-600">88%</span>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t">
                        <a href="{{ route('parent.detailed-reports') }}" class="w-full bg-yellow-600 text-white py-2 px-4 rounded-lg hover:bg-yellow-700 transition-colors text-center block">
                            View All Reports
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Activity -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200/60">
                <div class="p-4 sm:p-6 border-b border-slate-200">
                    <h2 class="text-lg sm:text-xl font-semibold text-slate-900">Recent Activity</h2>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="text-center py-8 sm:py-12">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        @if(session('user.role') === 'student')
                            <h3 class="text-base sm:text-lg font-medium text-slate-900 mb-2">No activity yet</h3>
                            <p class="text-slate-500 mb-4 text-sm sm:text-base">Start practicing questions to see your activity here.</p>
                            <a href="{{ route('ai-chat.index') }}" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-2.5 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-medium text-sm sm:text-base transform hover:scale-[1.02] active:scale-[0.98]">
                                Start with AI Study Help
                            </a>
                        @elseif(session('user.role') === 'parent')
                            <h3 class="text-base sm:text-lg font-medium text-slate-900 mb-2">No activity yet</h3>
                            <p class="text-slate-500 mb-4 text-sm sm:text-base">Your children's activity will appear here once they start studying.</p>
                            <a href="{{ route('parent.children') }}" class="bg-gradient-to-r from-orange-600 to-red-600 text-white px-6 py-2.5 rounded-lg hover:from-orange-700 hover:to-red-700 transition-all duration-200 font-medium text-sm sm:text-base transform hover:scale-[1.02] active:scale-[0.98] inline-block">
                                View Children's Progress
                            </a>
                        @else
                            <h3 class="text-base sm:text-lg font-medium text-slate-900 mb-2">No activity yet</h3>
                            <p class="text-slate-500 mb-4 text-sm sm:text-base">Start creating questions to see your activity here.</p>
                            <a href="{{ route('pdf-upload.index') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2.5 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 font-medium text-sm sm:text-base transform hover:scale-[1.02] active:scale-[0.98] inline-block">
                                Create Your First Questions
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
