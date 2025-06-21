@extends('layouts.admin')

@section('title', 'System Reports')
@section('page-title', 'Reports')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">System Reports</h1>
                <p class="mt-1 text-sm text-gray-600">Comprehensive analytics and statistics for the Smart Study platform</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.export') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-download mr-2"></i>Export Data
                </a>
                <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- User Statistics -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-users mr-2 text-blue-600"></i>User Statistics
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $reports['user_statistics']['total_users'] }}</div>
                    <div class="text-sm text-gray-600">Total Users</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $reports['user_statistics']['active_users'] }}</div>
                    <div class="text-sm text-gray-600">Active Users</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600">{{ $reports['user_statistics']['recent_registrations'] }}</div>
                    <div class="text-sm text-gray-600">New This Month</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-orange-600">{{ count($reports['user_statistics']['users_by_role']) }}</div>
                    <div class="text-sm text-gray-600">User Roles</div>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-medium text-gray-900 mb-3">Users by Role</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($reports['user_statistics']['users_by_role'] as $role => $count)
                    <div class="text-center">
                        <div class="text-xl font-semibold text-gray-900">{{ $count }}</div>
                        <div class="text-sm text-gray-600 capitalize">{{ $role }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Content Statistics -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-book mr-2 text-green-600"></i>Content Statistics
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600">{{ $reports['content_statistics']['total_subjects'] }}</div>
                    <div class="text-sm text-gray-600">Total Subjects</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $reports['content_statistics']['total_notes'] }}</div>
                    <div class="text-sm text-gray-600">Total Notes</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $reports['content_statistics']['published_notes'] }}</div>
                    <div class="text-sm text-gray-600">Published Notes</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-yellow-600">{{ $reports['content_statistics']['draft_notes'] }}</div>
                    <div class="text-sm text-gray-600">Draft Notes</div>
                </div>
            </div>
            
            @if(count($reports['content_statistics']['notes_by_subject']) > 0)
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-medium text-gray-900 mb-3">Notes by Subject</h4>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    @foreach($reports['content_statistics']['notes_by_subject'] as $subject => $count)
                    <div class="text-center">
                        <div class="text-xl font-semibold text-gray-900">{{ $count }}</div>
                        <div class="text-sm text-gray-600">{{ Str::limit($subject, 15) }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Q&A Statistics -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-question-circle mr-2 text-yellow-600"></i>Q&A System Statistics
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-yellow-600">{{ $reports['qa_statistics']['total_questions'] }}</div>
                    <div class="text-sm text-gray-600">Total Questions</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $reports['qa_statistics']['total_answers'] }}</div>
                    <div class="text-sm text-gray-600">Total Answers</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $reports['qa_statistics']['correct_answers'] }}</div>
                    <div class="text-sm text-gray-600">Correct Answers</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600">{{ $reports['qa_statistics']['ai_generated_questions'] }}</div>
                    <div class="text-sm text-gray-600">AI Generated</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-orange-600">{{ $reports['qa_statistics']['manual_questions'] }}</div>
                    <div class="text-sm text-gray-600">Manual Questions</div>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-medium text-gray-900 mb-3">Q&A Performance</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-xl font-semibold text-gray-900">
                            {{ $reports['qa_statistics']['total_answers'] > 0 ? round(($reports['qa_statistics']['correct_answers'] / $reports['qa_statistics']['total_answers']) * 100, 1) : 0 }}%
                        </div>
                        <div class="text-sm text-gray-600">Answer Accuracy</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xl font-semibold text-gray-900">
                            {{ $reports['qa_statistics']['total_questions'] > 0 ? round($reports['qa_statistics']['total_answers'] / $reports['qa_statistics']['total_questions'], 1) : 0 }}
                        </div>
                        <div class="text-sm text-gray-600">Avg Answers per Question</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xl font-semibold text-gray-900">
                            {{ $reports['qa_statistics']['total_questions'] > 0 ? round(($reports['qa_statistics']['ai_generated_questions'] / $reports['qa_statistics']['total_questions']) * 100, 1) : 0 }}%
                        </div>
                        <div class="text-sm text-gray-600">AI Generation Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Statistics -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-comments mr-2 text-red-600"></i>Feedback Statistics
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-red-600">{{ $reports['feedback_statistics']['total_feedback'] }}</div>
                    <div class="text-sm text-gray-600">Total Feedback</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-yellow-600">{{ $reports['feedback_statistics']['average_rating'] }}</div>
                    <div class="text-sm text-gray-600">Average Rating</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $reports['feedback_statistics']['recent_feedback'] }}</div>
                    <div class="text-sm text-gray-600">This Month</div>
                </div>
            </div>
            
            @if(count($reports['feedback_statistics']['feedback_by_rating']) > 0)
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-medium text-gray-900 mb-3">Feedback Distribution</h4>
                <div class="grid grid-cols-5 gap-4">
                    @for($i = 1; $i <= 5; $i++)
                    <div class="text-center">
                        <div class="text-xl font-semibold text-gray-900">{{ $reports['feedback_statistics']['feedback_by_rating'][$i] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</div>
                        <div class="flex justify-center mt-1">
                            @for($j = 1; $j <= $i; $j++)
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                            @endfor
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- System Health -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-heartbeat mr-2 text-red-600"></i>System Health Overview
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-database text-green-600 text-xl"></i>
                    </div>
                    <div class="text-lg font-semibold text-gray-900">{{ $reports['system_health']['database_status'] }}</div>
                    <div class="text-sm text-gray-600">Database Status</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ number_format($reports['system_health']['total_records']) }}</div>
                    <div class="text-sm text-gray-600">Total Records</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $reports['system_health']['system_uptime'] }}</div>
                    <div class="text-sm text-gray-600">System Uptime</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600">{{ $reports['system_health']['last_backup'] }}</div>
                    <div class="text-sm text-gray-600">Last Backup</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Generation Info -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-blue-600 mr-3"></i>
            <div>
                <h4 class="font-medium text-blue-900">Report Information</h4>
                <p class="text-sm text-blue-700">
                    This report was generated on {{ now()->format('F j, Y \a\t g:i A') }}. 
                    Data reflects the current state of the Smart Study platform.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    console.log('Admin Reports page loaded');
    
    // Add any report-specific JavaScript here
    document.addEventListener('DOMContentLoaded', function() {
        // Could add chart.js or other visualization libraries here
        console.log('Reports dashboard ready');
    });
</script>
@endsection
