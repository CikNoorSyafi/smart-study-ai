@extends('teacher.layout')

@section('title', $subject->name . ' - Analytics')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <a href="{{ route('teacher.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-home"></i>
                        <span class="sr-only">Dashboard</span>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('teacher.subjects.detail', $subject) }}" class="text-gray-500 hover:text-gray-700">{{ $subject->name }}</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-gray-900">Analytics</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-3xl font-bold text-gray-900 mt-2">{{ $subject->name }} - Analytics</h1>
        <p class="mt-1 text-sm text-gray-600">Performance metrics and insights for this subject.</p>
    </div>
    <div class="flex space-x-3">
        <button class="bg-secondary text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
            <i class="fas fa-download mr-2"></i>Export Report
        </button>
        <a href="{{ route('teacher.subjects.detail', $subject) }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Subject
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <!-- Analytics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Student Engagement -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-users text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Student Engagement</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $analytics['student_engagement']['engagement_rate'] }}%</dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-center text-sm">
                        <span class="text-green-600 font-medium">{{ $analytics['student_engagement']['active_students'] }}/{{ $analytics['student_engagement']['total_students'] }}</span>
                        <span class="text-gray-500 ml-2">active students</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Performance -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Avg. Note Views</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $analytics['content_performance']['avg_note_views'] }}</dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-center text-sm">
                        <span class="text-blue-600 font-medium">{{ $analytics['content_performance']['total_notes'] }}</span>
                        <span class="text-gray-500 ml-2">total notes</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Question Analytics -->
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
                            <dt class="text-sm font-medium text-gray-500 truncate">Avg. Quiz Score</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $analytics['question_analytics']['avg_score'] }}%</dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-center text-sm">
                        <span class="text-purple-600 font-medium">{{ $analytics['question_analytics']['completion_rate'] }}%</span>
                        <span class="text-gray-500 ml-2">completion rate</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Student Performance Chart -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-chart-line text-primary mr-2"></i>
                    Student Performance Trends
                </h3>
            </div>
            <div class="p-6">
                <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                    <div class="text-center">
                        <i class="fas fa-chart-line text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Performance chart will be implemented</p>
                        <p class="text-sm text-gray-400">Shows student progress over time</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Engagement -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-eye text-secondary mr-2"></i>
                    Content Engagement
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-900">Most Viewed Note</span>
                        <span class="text-sm text-gray-500">{{ $analytics['content_performance']['most_viewed_note'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-900">Average Views per Note</span>
                        <span class="text-sm text-gray-500">{{ $analytics['content_performance']['avg_note_views'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-900">Total Questions</span>
                        <span class="text-sm text-gray-500">{{ $analytics['question_analytics']['total_questions'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-900">Question Completion Rate</span>
                        <span class="text-sm text-gray-500">{{ $analytics['question_analytics']['completion_rate'] }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Activity -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-clock text-accent mr-2"></i>
                    Recent Student Activity
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm text-gray-900">Alice Johnson completed quiz</p>
                            <p class="text-xs text-gray-500">2 hours ago • Score: 85%</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-eye text-blue-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm text-gray-900">Bob Smith viewed study note</p>
                            <p class="text-xs text-gray-500">4 hours ago • "Introduction to Programming"</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                <i class="fas fa-question-circle text-yellow-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm text-gray-900">Carol Davis started quiz</p>
                            <p class="text-xs text-gray-500">6 hours ago • In progress</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Insights -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                    Performance Insights
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-thumbs-up text-green-600"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-green-800">High Engagement</h4>
                                <p class="text-sm text-green-700">Students are actively participating in {{ $subject->name }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-600"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-blue-800">Content Performance</h4>
                                <p class="text-sm text-blue-700">Your study notes are getting good engagement</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-yellow-800">Opportunity</h4>
                                <p class="text-sm text-yellow-700">Consider creating more interactive content</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Reports -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-file-chart-line text-primary mr-2"></i>
                Detailed Reports
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button class="p-4 border-2 border-gray-200 rounded-lg hover:border-primary hover:shadow-md transition-all text-left">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="font-medium text-gray-900">Student Progress Report</h4>
                            <p class="text-sm text-gray-500">Individual student performance</p>
                        </div>
                    </div>
                </button>

                <button class="p-4 border-2 border-gray-200 rounded-lg hover:border-secondary hover:shadow-md transition-all text-left">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-alt text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="font-medium text-gray-900">Content Analytics</h4>
                            <p class="text-sm text-gray-500">Note and question performance</p>
                        </div>
                    </div>
                </button>

                <button class="p-4 border-2 border-gray-200 rounded-lg hover:border-accent hover:shadow-md transition-all text-left">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chart-bar text-yellow-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="font-medium text-gray-900">Engagement Report</h4>
                            <p class="text-sm text-gray-500">Activity and participation metrics</p>
                        </div>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
