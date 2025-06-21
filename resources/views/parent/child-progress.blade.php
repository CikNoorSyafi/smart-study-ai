@extends('layouts.app')

@section('title', $child['name'] . ' Progress - Smart Study')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-{{ $child['avatar_color'] }}-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-{{ $child['avatar_color'] }}-600 font-bold text-xl">{{ $child['avatar'] }}</span>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900">{{ $child['name'] }}</h1>
                        <p class="text-slate-600">{{ $child['grade'] }} • Overall Grade: <span class="font-medium text-{{ $child['avatar_color'] }}-600">{{ $child['overall_grade'] }}</span></p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('parent.children') }}" class="bg-slate-600 text-white px-4 py-2 rounded-lg hover:bg-slate-700 transition-colors">
                        ← Back to Children
                    </a>
                    <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Overview Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm">Overall Grade</p>
                        <p class="text-2xl font-bold text-{{ $child['avatar_color'] }}-600">{{ $child['overall_grade'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-{{ $child['avatar_color'] }}-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-{{ $child['avatar_color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm">Attendance</p>
                        <p class="text-2xl font-bold text-green-600">{{ $child['attendance'] }}%</p>
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
                        <p class="text-slate-600 text-sm">Daily Study Time</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $child['study_time']['daily_average'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm">Most Studied</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $child['study_time']['most_studied'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subject Progress -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-6">Subject Performance</h3>
                <div class="space-y-4">
                    @foreach($child['detailed_progress'] as $subject => $progress)
                    @if(in_array($subject, $child['subjects']))
                    <div class="p-4 bg-slate-50 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="font-medium text-slate-900">{{ $subject }}</h4>
                            <span class="text-lg font-bold text-{{ $child['avatar_color'] }}-600">{{ $progress['current_grade'] }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-600">Last Test: {{ $progress['last_test'] }}%</span>
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if($progress['trend'] === 'excellent' || $progress['trend'] === 'outstanding') bg-green-100 text-green-700
                                @elseif($progress['trend'] === 'improving') bg-blue-100 text-blue-700
                                @else bg-yellow-100 text-yellow-700 @endif">
                                {{ ucfirst($progress['trend']) }}
                            </span>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-6">Recent Achievements</h3>
                <div class="space-y-3">
                    @foreach($child['achievements'] as $achievement)
                    <div class="flex items-center p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                        <div class="w-8 h-8 bg-yellow-600 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                        </div>
                        <span class="text-slate-900 font-medium">{{ $achievement }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Study Analytics -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
            <h3 class="text-lg font-semibold text-slate-900 mb-6">Study Analytics</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600 mb-2">{{ $child['study_time']['weekly_total'] }}</div>
                    <div class="text-sm text-slate-600">Weekly Study Time</div>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600 mb-2">{{ count($child['subjects']) }}</div>
                    <div class="text-sm text-slate-600">Active Subjects</div>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600 mb-2">{{ count($child['achievements']) }}</div>
                    <div class="text-sm text-slate-600">Achievements</div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                    Contact Teachers
                </a>
                <a href="{{ route('parent.manage-children') }}" class="flex items-center justify-center p-4 bg-{{ $child['avatar_color'] }}-600 text-white rounded-lg hover:bg-{{ $child['avatar_color'] }}-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Manage Info
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
