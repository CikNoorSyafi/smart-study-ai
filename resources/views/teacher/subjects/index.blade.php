@extends('teacher.layout')

@section('title', 'My Subjects')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">My Subjects</h1>
        <p class="mt-1 text-sm text-gray-600">Manage your assigned subjects and track student progress.</p>
    </div>
    <div class="flex space-x-3">
        <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
            <i class="fas fa-plus mr-2"></i>Request New Subject
        </button>
    </div>
</div>
@endsection

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Assigned Subjects ({{ $subjects->total() }})</h3>
            <div class="flex space-x-2">
                <select class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option>All Subjects</option>
                    <option>Active</option>
                    <option>Inactive</option>
                </select>
                <div class="relative">
                    <input type="text" placeholder="Search subjects..." class="border border-gray-300 rounded-md pl-10 pr-4 py-2 text-sm">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
        </div>
    </div>

    @if($subjects->count() > 0)
    <div class="overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
            @foreach($subjects as $subject)
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="text-lg font-medium text-gray-900 mb-2">{{ $subject->name }}</h4>
                        <p class="text-sm text-gray-600 mb-4">{{ Str::limit($subject->description ?? 'No description available', 100) }}</p>
                        
                        <div class="space-y-2">
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-users mr-2"></i>
                                <span>{{ $subject->student_count }} students enrolled</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-file-alt mr-2"></i>
                                <span>{{ $subject->notes_count }} study notes</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-calendar mr-2"></i>
                                <span>Created {{ $subject->created_at->format('M j, Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="ml-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    </div>
                </div>

                <div class="mt-6 flex space-x-3">
                    <button class="flex-1 bg-primary text-white px-3 py-2 rounded-md text-sm hover:bg-blue-600 transition-colors">
                        <i class="fas fa-eye mr-1"></i>
                        View Details
                    </button>
                    <button class="flex-1 bg-gray-100 text-gray-700 px-3 py-2 rounded-md text-sm hover:bg-gray-200 transition-colors">
                        <i class="fas fa-users mr-1"></i>
                        Students
                    </button>
                </div>

                <!-- Progress Bar -->
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                        <span>Course Progress</span>
                        <span>{{ rand(60, 95) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-primary h-2 rounded-full" style="width: {{ rand(60, 95) }}%"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Pagination -->
    @if($subjects->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $subjects->links() }}
    </div>
    @endif

    @else
    <div class="text-center py-12">
        <i class="fas fa-book text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No subjects assigned</h3>
        <p class="text-gray-500 mb-6">You haven't been assigned to any subjects yet. Contact your administrator to get started.</p>
        <button class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-colors">
            <i class="fas fa-envelope mr-2"></i>Contact Administrator
        </button>
    </div>
    @endif
</div>

<!-- Subject Statistics -->
@if($subjects->count() > 0)
<div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                    <i class="fas fa-book text-white"></i>
                </div>
            </div>
            <div class="ml-5">
                <p class="text-sm font-medium text-gray-500">Total Subjects</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $subjects->total() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                    <i class="fas fa-users text-white"></i>
                </div>
            </div>
            <div class="ml-5">
                <p class="text-sm font-medium text-gray-500">Total Students</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $subjects->sum('student_count') }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                    <i class="fas fa-file-alt text-white"></i>
                </div>
            </div>
            <div class="ml-5">
                <p class="text-sm font-medium text-gray-500">Total Notes</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $subjects->sum('notes_count') }}</p>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
