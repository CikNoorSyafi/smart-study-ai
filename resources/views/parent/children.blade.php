@extends('layouts.app')

@section('title', 'My Children - Smart Study')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 mb-2">My Children</h1>
                    <p class="text-slate-600">Monitor your children's academic progress and performance</p>
                </div>
                <a href="{{ route('dashboard') }}" class="bg-slate-600 text-white px-4 py-2 rounded-lg hover:bg-slate-700 transition-colors">
                    ← Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Children Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($children as $child)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <!-- Child Header -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-16 h-16 bg-{{ $child['avatar_color'] }}-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-{{ $child['avatar_color'] }}-600 font-bold text-xl">{{ $child['avatar'] }}</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-slate-900">{{ $child['name'] }}</h3>
                            <p class="text-slate-600">{{ $child['grade'] }}</p>
                            <p class="text-sm text-slate-500">Overall Grade: <span class="font-medium text-{{ $child['avatar_color'] }}-600">{{ $child['overall_grade'] }}</span></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-green-600">{{ $child['attendance'] }}%</div>
                        <div class="text-sm text-slate-500">Attendance</div>
                    </div>
                </div>

                <!-- Subjects -->
                <div class="mb-6">
                    <h4 class="font-medium text-slate-900 mb-3">Subjects</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($child['subjects'] as $subject)
                        <span class="bg-{{ $child['avatar_color'] }}-100 text-{{ $child['avatar_color'] }}-700 px-3 py-1 rounded-full text-sm">{{ $subject }}</span>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Scores -->
                <div class="mb-6">
                    <h4 class="font-medium text-slate-900 mb-3">Recent Scores</h4>
                    <div class="space-y-2">
                        @foreach(array_slice($child['recent_scores'], 0, 3) as $score)
                        <div class="flex justify-between items-center p-2 bg-slate-50 rounded">
                            <span class="text-sm text-slate-700">{{ $score['subject'] }}</span>
                            <div class="flex items-center">
                                <span class="font-medium text-slate-900 mr-2">{{ $score['score'] }}%</span>
                                <span class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($score['date'])->format('M j') }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Upcoming Tests -->
                <div class="mb-6">
                    <h4 class="font-medium text-slate-900 mb-3">Upcoming Tests</h4>
                    <div class="space-y-2">
                        @foreach($child['upcoming_tests'] as $test)
                        <div class="flex justify-between items-center p-2 bg-yellow-50 rounded border border-yellow-200">
                            <div>
                                <span class="text-sm font-medium text-slate-900">{{ $test['subject'] }}</span>
                                <span class="text-xs text-slate-600 ml-2">{{ $test['type'] }}</span>
                            </div>
                            <span class="text-xs text-yellow-700 font-medium">{{ \Carbon\Carbon::parse($test['date'])->format('M j') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Teacher Notes -->
                <div class="mb-6">
                    <h4 class="font-medium text-slate-900 mb-3">Teacher Notes</h4>
                    <div class="space-y-2">
                        @foreach($child['teacher_notes'] as $note)
                        <div class="p-3 bg-blue-50 rounded border border-blue-200">
                            <p class="text-sm text-slate-700">{{ $note }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <a href="{{ route('parent.child.progress', $child['id']) }}" class="flex-1 bg-{{ $child['avatar_color'] }}-600 text-white py-2 px-4 rounded-lg hover:bg-{{ $child['avatar_color'] }}-700 transition-colors text-center text-sm font-medium">
                        View Detailed Progress
                    </a>
                    <a href="{{ route('parent.messages') }}" class="flex-1 bg-slate-600 text-white py-2 px-4 rounded-lg hover:bg-slate-700 transition-colors text-center text-sm font-medium">
                        Contact Teachers
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('parent.reports') }}" class="flex items-center p-4 bg-yellow-50 rounded-lg border border-yellow-200 hover:bg-yellow-100 transition-colors">
                    <div class="w-10 h-10 bg-yellow-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-medium text-slate-900">Performance Reports</div>
                        <div class="text-sm text-slate-600">View detailed reports</div>
                    </div>
                </a>

                <a href="{{ route('parent.messages') }}" class="flex items-center p-4 bg-green-50 rounded-lg border border-green-200 hover:bg-green-100 transition-colors">
                    <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-medium text-slate-900">Messages</div>
                        <div class="text-sm text-slate-600">Communicate with teachers</div>
                    </div>
                </a>

                <a href="{{ route('parent.manage-children') }}" class="flex items-center p-4 bg-orange-50 rounded-lg border border-orange-200 hover:bg-orange-100 transition-colors">
                    <div class="w-10 h-10 bg-orange-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-medium text-slate-900">Manage Children</div>
                        <div class="text-sm text-slate-600">Update information</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
