@extends('teacher.layout')

@section('title', 'My Students')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">My Students</h1>
        <p class="mt-1 text-sm text-gray-600">Monitor and manage students across your subjects.</p>
    </div>
    <div class="flex space-x-3">
        <button class="bg-secondary text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
            <i class="fas fa-download mr-2"></i>Export List
        </button>
        <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
            <i class="fas fa-envelope mr-2"></i>Send Message
        </button>
    </div>
</div>
@endsection

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Students ({{ $students->total() }})</h3>
            <div class="flex space-x-2">
                <select class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option>All Subjects</option>
                    @foreach(auth()->user()->subjects()->wherePivot('role_in_subject', 'teacher')->get() as $subject)
                    <option value="{{ $subject->subject_id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
                <select class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option>All Status</option>
                    <option>Active</option>
                    <option>Inactive</option>
                </select>
                <div class="relative">
                    <input type="text" placeholder="Search students..." class="border border-gray-300 rounded-md pl-10 pr-4 py-2 text-sm">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
        </div>
    </div>

    @if($students->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subjects</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Activity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($students as $student)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full" src="{{ $student->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&background=3B82F6&color=fff' }}" alt="{{ $student->name }}">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                <div class="text-sm text-gray-500">{{ $student->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-wrap gap-1">
                            @foreach($student->subjects as $subject)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $subject->name }}
                            </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-1">
                                @php $progress = rand(40, 95); @endphp
                                <div class="flex justify-between text-xs text-gray-500 mb-1">
                                    <span>Overall</span>
                                    <span>{{ $progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-primary h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-1"></i>
                            {{ $student->updated_at->diffForHumans() }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($student->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-circle mr-1 text-green-400"></i>
                            Active
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <i class="fas fa-circle mr-1 text-red-400"></i>
                            Inactive
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="text-primary hover:text-blue-600" title="View Profile">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-secondary hover:text-green-600" title="Send Message">
                                <i class="fas fa-envelope"></i>
                            </button>
                            <button class="text-accent hover:text-yellow-600" title="View Progress">
                                <i class="fas fa-chart-line"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($students->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $students->links() }}
    </div>
    @endif

    @else
    <div class="text-center py-12">
        <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No students found</h3>
        <p class="text-gray-500 mb-6">No students are enrolled in your subjects yet.</p>
        <button class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-colors">
            <i class="fas fa-user-plus mr-2"></i>Invite Students
        </button>
    </div>
    @endif
</div>

<!-- Student Statistics -->
@if($students->count() > 0)
<div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                    <i class="fas fa-users text-white"></i>
                </div>
            </div>
            <div class="ml-5">
                <p class="text-sm font-medium text-gray-500">Total Students</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $students->total() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                    <i class="fas fa-user-check text-white"></i>
                </div>
            </div>
            <div class="ml-5">
                <p class="text-sm font-medium text-gray-500">Active Students</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $students->where('is_active', true)->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                    <i class="fas fa-chart-line text-white"></i>
                </div>
            </div>
            <div class="ml-5">
                <p class="text-sm font-medium text-gray-500">Avg. Progress</p>
                <p class="text-2xl font-semibold text-gray-900">{{ rand(70, 85) }}%</p>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                    <i class="fas fa-clock text-white"></i>
                </div>
            </div>
            <div class="ml-5">
                <p class="text-sm font-medium text-gray-500">Active Today</p>
                <p class="text-2xl font-semibold text-gray-900">{{ rand(5, 15) }}</p>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
