@extends('teacher.layout')

@section('title', 'Student Management Hub')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Student Management Hub</h1>
        <p class="mt-1 text-sm text-gray-600">Comprehensive student management and analytics for your subjects.</p>
    </div>
    <div class="flex space-x-3">
        <button class="bg-secondary text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
            <i class="fas fa-download mr-2"></i>Export Data
        </button>
        <a href="{{ route('teacher.dashboard') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <!-- Student Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
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
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Students</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $studentStats['total_students'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-user-check text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Students</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $studentStats['active_students'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-chart-line text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Avg. Progress</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $studentStats['avg_progress'] }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-user-plus text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">New This Week</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $studentStats['students_this_week'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Primary Actions -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-cogs text-primary mr-2"></i>
                        Student Management Actions
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- View All Students -->
                        <a href="{{ route('teacher.students') }}" class="block p-6 border-2 border-gray-200 rounded-lg hover:border-primary hover:shadow-md transition-all">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-list text-2xl text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">View All Students</h4>
                                    <p class="text-sm text-gray-600">Complete student list with progress tracking</p>
                                </div>
                            </div>
                        </a>

                        <!-- Assign Students -->
                        <button onclick="openAssignModal()" class="block w-full p-6 border-2 border-gray-200 rounded-lg hover:border-secondary hover:shadow-md transition-all text-left">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-user-plus text-2xl text-green-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">Assign Students</h4>
                                    <p class="text-sm text-gray-600">Add students to your subjects</p>
                                </div>
                            </div>
                        </button>

                        <!-- Performance Analytics -->
                        <a href="{{ route('teacher.analytics') }}" class="block p-6 border-2 border-gray-200 rounded-lg hover:border-accent hover:shadow-md transition-all">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-chart-bar text-2xl text-yellow-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">Performance Analytics</h4>
                                    <p class="text-sm text-gray-600">Detailed progress and engagement metrics</p>
                                </div>
                            </div>
                        </a>

                        <!-- Send Messages -->
                        <button onclick="openMessageModal()" class="block w-full p-6 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:shadow-md transition-all text-left">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-envelope text-2xl text-purple-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">Send Messages</h4>
                                    <p class="text-sm text-gray-600">Communicate with students and parents</p>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subject Distribution -->
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-chart-pie text-primary mr-2"></i>
                        Subject Distribution
                    </h3>
                </div>
                <div class="p-6">
                    @if(count($subjectDistribution) > 0)
                        <div class="space-y-4">
                            @foreach($subjectDistribution as $item)
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex justify-between text-sm font-medium text-gray-900 mb-1">
                                        <span>{{ $item['subject']->name }}</span>
                                        <span>{{ $item['student_count'] }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-primary h-2 rounded-full" style="width: {{ $item['percentage'] }}%"></div>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                                        <span>{{ $item['percentage'] }}% of total</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No subject data available</p>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <h3 class="text-lg font-medium text-green-900 mb-4">
                    <i class="fas fa-rocket text-green-600 mr-2"></i>
                    Quick Actions
                </h3>
                <div class="space-y-3">
                    <button onclick="bulkMessage()" class="w-full text-left p-3 bg-white border border-green-200 rounded-lg hover:bg-green-50 transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-bullhorn text-green-600 mr-3"></i>
                            <span class="text-sm font-medium text-green-800">Send Announcement</span>
                        </div>
                    </button>
                    <button onclick="exportStudents()" class="w-full text-left p-3 bg-white border border-green-200 rounded-lg hover:bg-green-50 transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-download text-green-600 mr-3"></i>
                            <span class="text-sm font-medium text-green-800">Export Student List</span>
                        </div>
                    </button>
                    <button onclick="generateReport()" class="w-full text-left p-3 bg-white border border-green-200 rounded-lg hover:bg-green-50 transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-file-pdf text-green-600 mr-3"></i>
                            <span class="text-sm font-medium text-green-800">Generate Report</span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Student Activity -->
    @if($students->count() > 0)
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-clock text-primary mr-2"></i>
                Recent Student Activity
            </h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($students->take(5) as $student)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <img class="h-10 w-10 rounded-full" src="{{ $student->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&background=3B82F6&color=fff' }}" alt="{{ $student->name }}">
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">{{ $student->name }}</p>
                            <p class="text-sm text-gray-500">Last active {{ $student->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $student->subjects->count() }} subjects
                        </span>
                        <button class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Assign Students Modal -->
<div id="assign-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Assign Students to Subject</h3>
                <button onclick="closeAssignModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Subject</label>
                    <select class="w-full border border-gray-300 rounded-md px-3 py-2">
                        @foreach($teacherSubjects as $subject)
                        <option value="{{ $subject->subject_id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Available Students</label>
                    <div class="max-h-48 overflow-y-auto border border-gray-200 rounded-md p-3">
                        @foreach($unassignedStudents->take(10) as $student)
                        <label class="flex items-center mb-2">
                            <input type="checkbox" class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">{{ $student->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button onclick="closeAssignModal()" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                    Assign Students
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openAssignModal() {
    document.getElementById('assign-modal').classList.remove('hidden');
}

function closeAssignModal() {
    document.getElementById('assign-modal').classList.add('hidden');
}

function openMessageModal() {
    alert('Message functionality will be implemented in Phase 3');
}

function bulkMessage() {
    alert('Bulk messaging functionality coming soon');
}

function exportStudents() {
    alert('Export functionality will be implemented');
}

function generateReport() {
    alert('Report generation functionality coming soon');
}
</script>
@endsection
