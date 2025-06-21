@extends('teacher.layout')

@section('title', $subject->name . ' - Students')

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
                        <span class="text-gray-900">Students</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-3xl font-bold text-gray-900 mt-2">{{ $subject->name }} - Students</h1>
        <p class="mt-1 text-sm text-gray-600">Manage students enrolled in this subject.</p>
    </div>
    <div class="flex space-x-3">
        <button onclick="openAssignModal()" class="bg-secondary text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
            <i class="fas fa-user-plus mr-2"></i>Assign Students
        </button>
        <a href="{{ route('teacher.subjects.detail', $subject) }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Subject
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <!-- Students List -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-users text-primary mr-2"></i>
                    Enrolled Students ({{ $students->total() }})
                </h3>
                <div class="flex space-x-2">
                    <button class="text-sm text-gray-600 hover:text-gray-800">
                        <i class="fas fa-download mr-1"></i>Export
                    </button>
                    <button class="text-sm text-gray-600 hover:text-gray-800">
                        <i class="fas fa-filter mr-1"></i>Filter
                    </button>
                </div>
            </div>
        </div>
        <div class="p-6">
            @if($students->count() > 0)
                <div class="space-y-4">
                    @foreach($students as $student)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="flex items-center">
                            <img class="h-12 w-12 rounded-full" src="{{ $student->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&background=3B82F6&color=fff' }}" alt="{{ $student->name }}">
                            <div class="ml-4">
                                <h4 class="font-medium text-gray-900">{{ $student->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $student->email }}</p>
                                <p class="text-xs text-gray-400">Enrolled {{ $student->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                                <p class="text-xs text-gray-500 mt-1">Progress: 75%</p>
                            </div>
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-800" title="View Progress">
                                    <i class="fas fa-chart-line"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-800" title="Send Message">
                                    <i class="fas fa-envelope"></i>
                                </button>
                                <button onclick="removeStudent('{{ $student->user_id }}')" class="text-red-600 hover:text-red-800" title="Remove from Subject">
                                    <i class="fas fa-user-minus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $students->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No students enrolled</h3>
                    <p class="text-gray-500 mb-4">Start by assigning students to this subject.</p>
                    <button onclick="openAssignModal()" class="bg-secondary text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
                        <i class="fas fa-user-plus mr-2"></i>Assign First Student
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Assign Students Modal -->
<div id="assign-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Assign Students to {{ $subject->name }}</h3>
                <button onclick="closeAssignModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Available Students</label>
                    <div class="max-h-48 overflow-y-auto border border-gray-200 rounded-md p-3">
                        @if($unassignedStudents->count() > 0)
                            @foreach($unassignedStudents as $student)
                            <label class="flex items-center mb-2">
                                <input type="checkbox" class="rounded border-gray-300 text-primary focus:ring-primary" value="{{ $student->user_id }}">
                                <span class="ml-2 text-sm text-gray-700">{{ $student->name }}</span>
                            </label>
                            @endforeach
                        @else
                            <p class="text-sm text-gray-500">No unassigned students available.</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button onclick="closeAssignModal()" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button onclick="assignStudents()" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
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

function assignStudents() {
    const checkboxes = document.querySelectorAll('#assign-modal input[type="checkbox"]:checked');
    const studentIds = Array.from(checkboxes).map(cb => cb.value);
    
    if (studentIds.length === 0) {
        alert('Please select at least one student to assign.');
        return;
    }
    
    // TODO: Implement actual assignment logic
    alert(`Assigning ${studentIds.length} students to {{ $subject->name }}. This functionality will be implemented.`);
    closeAssignModal();
}

function removeStudent(studentId) {
    if (confirm('Are you sure you want to remove this student from {{ $subject->name }}?')) {
        // TODO: Implement actual removal logic
        alert('Student removal functionality will be implemented.');
    }
}
</script>
@endsection
