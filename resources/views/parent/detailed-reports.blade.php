@extends('layouts.app')

@section('title', 'Detailed Reports - Smart Study')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 mb-2">Detailed Analytics</h1>
                    <p class="text-slate-600">Comprehensive performance analytics and insights for your children</p>
                </div>
                <a href="{{ route('dashboard') }}" class="bg-slate-600 text-white px-4 py-2 rounded-lg hover:bg-slate-700 transition-colors">
                    ← Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Overview Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm">Overall Family GPA</p>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format(($analytics['academic_performance']['john']['overall_gpa'] + $analytics['academic_performance']['emma']['overall_gpa']) / 2, 1) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm">Average Attendance</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format(($analytics['attendance_data']['john']['present'] + $analytics['attendance_data']['emma']['present']) / 2, 1) }}%</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm">Positive Notes</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $analytics['behavioral_reports']['positive_notes'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm">Commendations</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $analytics['behavioral_reports']['teacher_commendations'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Individual Performance Comparison -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- John's Performance -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-blue-600 font-bold">JS</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">John Smith Jr.</h3>
                        <p class="text-slate-600">Grade 8 • GPA: {{ $analytics['academic_performance']['john']['overall_gpa'] }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <h4 class="font-medium text-slate-900 mb-3">Subject Performance</h4>
                        @foreach($analytics['academic_performance']['john']['subjects'] as $subject => $data)
                        <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg mb-2">
                            <span class="font-medium text-slate-900">{{ $subject }}</span>
                            <div class="text-right">
                                <span class="font-bold text-blue-600">{{ $data['grade'] }}</span>
                                <span class="text-sm text-green-600 ml-2">{{ $data['improvement'] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div>
                        <h4 class="font-medium text-slate-900 mb-3">Attendance</h4>
                        <div class="grid grid-cols-3 gap-3">
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <div class="text-lg font-bold text-green-600">{{ $analytics['attendance_data']['john']['present'] }}%</div>
                                <div class="text-xs text-slate-600">Present</div>
                            </div>
                            <div class="text-center p-3 bg-red-50 rounded-lg">
                                <div class="text-lg font-bold text-red-600">{{ $analytics['attendance_data']['john']['absent'] }}%</div>
                                <div class="text-xs text-slate-600">Absent</div>
                            </div>
                            <div class="text-center p-3 bg-yellow-50 rounded-lg">
                                <div class="text-lg font-bold text-yellow-600">{{ $analytics['attendance_data']['john']['late'] }}%</div>
                                <div class="text-xs text-slate-600">Late</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Emma's Performance -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-pink-600 font-bold">ES</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Emma Smith</h3>
                        <p class="text-slate-600">Grade 6 • GPA: {{ $analytics['academic_performance']['emma']['overall_gpa'] }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <h4 class="font-medium text-slate-900 mb-3">Subject Performance</h4>
                        @foreach($analytics['academic_performance']['emma']['subjects'] as $subject => $data)
                        <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg mb-2">
                            <span class="font-medium text-slate-900">{{ $subject }}</span>
                            <div class="text-right">
                                <span class="font-bold text-pink-600">{{ $data['grade'] }}</span>
                                <span class="text-sm text-green-600 ml-2">{{ $data['improvement'] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div>
                        <h4 class="font-medium text-slate-900 mb-3">Attendance</h4>
                        <div class="grid grid-cols-3 gap-3">
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <div class="text-lg font-bold text-green-600">{{ $analytics['attendance_data']['emma']['present'] }}%</div>
                                <div class="text-xs text-slate-600">Present</div>
                            </div>
                            <div class="text-center p-3 bg-red-50 rounded-lg">
                                <div class="text-lg font-bold text-red-600">{{ $analytics['attendance_data']['emma']['absent'] }}%</div>
                                <div class="text-xs text-slate-600">Absent</div>
                            </div>
                            <div class="text-center p-3 bg-yellow-50 rounded-lg">
                                <div class="text-lg font-bold text-yellow-600">{{ $analytics['attendance_data']['emma']['late'] }}%</div>
                                <div class="text-xs text-slate-600">Late</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Behavioral Analytics -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
            <h3 class="text-lg font-semibold text-slate-900 mb-6">Behavioral Analytics</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-6 bg-green-50 rounded-lg">
                    <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-green-600 mb-2">{{ $analytics['behavioral_reports']['positive_notes'] }}</div>
                    <div class="text-sm text-slate-600">Positive Behavior Notes</div>
                    <div class="text-xs text-green-600 mt-1">Excellent progress!</div>
                </div>

                <div class="text-center p-6 bg-yellow-50 rounded-lg">
                    <div class="w-16 h-16 bg-yellow-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-yellow-600 mb-2">{{ $analytics['behavioral_reports']['areas_for_improvement'] }}</div>
                    <div class="text-sm text-slate-600">Areas for Improvement</div>
                    <div class="text-xs text-yellow-600 mt-1">Minor focus areas</div>
                </div>

                <div class="text-center p-6 bg-purple-50 rounded-lg">
                    <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-purple-600 mb-2">{{ $analytics['behavioral_reports']['teacher_commendations'] }}</div>
                    <div class="text-sm text-slate-600">Teacher Commendations</div>
                    <div class="text-xs text-purple-600 mt-1">Outstanding recognition!</div>
                </div>
            </div>
        </div>

        <!-- Trends and Insights -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
            <h3 class="text-lg font-semibold text-slate-900 mb-6">Trends & Insights</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-slate-900 mb-4">Academic Trends</h4>
                    <div class="space-y-3">
                        <div class="flex items-center p-3 bg-green-50 rounded-lg">
                            <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-slate-900">Math Performance</div>
                                <div class="text-sm text-slate-600">Both children showing improvement</div>
                            </div>
                        </div>
                        <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-slate-900">Overall GPA Trend</div>
                                <div class="text-sm text-slate-600">Steady upward trajectory</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-medium text-slate-900 mb-4">Behavioral Insights</h4>
                    <div class="space-y-3">
                        <div class="flex items-center p-3 bg-yellow-50 rounded-lg">
                            <div class="w-8 h-8 bg-yellow-600 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-slate-900">Excellent Behavior</div>
                                <div class="text-sm text-slate-600">Consistent positive feedback</div>
                            </div>
                        </div>
                        <div class="flex items-center p-3 bg-purple-50 rounded-lg">
                            <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-slate-900">Attendance</div>
                                <div class="text-sm text-slate-600">Above average attendance rates</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('parent.children') }}" class="flex items-center justify-center p-4 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    View Children
                </a>
                <a href="{{ route('parent.reports') }}" class="flex items-center justify-center p-4 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    View Reports
                </a>
                <a href="{{ route('parent.messages') }}" class="flex items-center justify-center p-4 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Messages
                </a>
                <a href="{{ route('parent.manage-children') }}" class="flex items-center justify-center p-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Manage
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
