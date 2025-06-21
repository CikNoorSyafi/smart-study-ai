@extends('layouts.admin')

@section('title', 'Subject Management')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Subject Management</h1>
                    <p class="mt-1 text-sm text-gray-600">Organize and manage academic subjects</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.subjects.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add New Subject
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Search and Filters -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-6">
                <form method="GET" action="{{ route('admin.subjects.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Subjects</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   placeholder="Subject name, code, or description..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="form_level" class="block text-sm font-medium text-gray-700 mb-2">Form Level</label>
                            <select name="form_level" id="form_level" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Forms</option>
                                @foreach($formLevels as $level)
                                <option value="{{ $level }}" {{ request('form_level') == $level ? 'selected' : '' }}>{{ $level }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select name="category" id="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                                <i class="fas fa-search mr-2"></i>Filter
                            </button>
                            <a href="{{ route('admin.subjects.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                                <i class="fas fa-times mr-2"></i>Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Malaysian Syllabus Subjects -->
        @if($subjects->count() > 0)

        @if(request('search') || request('form_level') || request('category'))
        <!-- Filtered Results -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">
                Search Results
                @if(request('form_level')) - {{ request('form_level') }} @endif
                @if(request('category')) - {{ request('category') }} @endif
                ({{ $subjects->total() }} subjects)
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($subjects as $subject)
                @include('admin.subjects.partials.subject-card', ['subject' => $subject])
                @endforeach
            </div>
        </div>
        @else
        <!-- Organized by Form Level and Category -->
        @foreach($groupedSubjects as $formLevel => $categories)
        <div class="mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-t-lg px-6 py-4">
                <h2 class="text-2xl font-bold">{{ $formLevel }} - Malaysian Secondary Education</h2>
                <p class="text-blue-100">SPM (Sijil Pelajaran Malaysia) Preparation Subjects</p>
            </div>

            @foreach($categories as $category => $categorySubjects)
            <div class="bg-white border-l-4 border-r border-b border-gray-200
                @if($category === 'Core') border-l-red-500
                @elseif($category === 'Science') border-l-green-500
                @elseif($category === 'Arts') border-l-yellow-500
                @elseif($category === 'Technical') border-l-purple-500
                @endif">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        @if($category === 'Core')
                        <i class="fas fa-star text-red-500 mr-2"></i>Core Subjects (Compulsory)
                        @elseif($category === 'Science')
                        <i class="fas fa-flask text-green-500 mr-2"></i>Science Stream
                        @elseif($category === 'Arts')
                        <i class="fas fa-palette text-yellow-500 mr-2"></i>Arts Stream
                        @elseif($category === 'Technical')
                        <i class="fas fa-cogs text-purple-500 mr-2"></i>Technical/Vocational
                        @endif
                        <span class="ml-2 text-sm text-gray-600">({{ $categorySubjects->count() }} subjects)</span>
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($categorySubjects as $subject)
                        @include('admin.subjects.partials.subject-card', ['subject' => $subject])
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
        @endif

        <!-- Pagination -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4">
                {{ $subjects->links() }}
            </div>
        </div>
        @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-12 text-center">
                <i class="fas fa-book text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No subjects found</h3>
                @if(request('search'))
                <p class="text-gray-500 mb-4">No subjects match your search criteria.</p>
                <a href="{{ route('admin.subjects.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors mr-2">
                    <i class="fas fa-times mr-2"></i>Clear Search
                </a>
                @else
                <p class="text-gray-500 mb-4">Get started by creating your first subject.</p>
                @endif
                <a href="{{ route('admin.subjects.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add First Subject
                </a>
            </div>
        </div>
        @endif

        <!-- Malaysian Syllabus Statistics -->
        <div class="mt-8 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Malaysian Secondary Education Statistics</h3>
                <p class="text-sm text-gray-600">SPM (Sijil Pelajaran Malaysia) Subject Overview</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-3xl font-bold text-blue-600">{{ $statistics['total_subjects'] }}</div>
                        <div class="text-sm text-gray-600">Total Subjects</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-3xl font-bold text-green-600">{{ $statistics['with_notes'] }}</div>
                        <div class="text-sm text-gray-600">With Notes</div>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <div class="text-3xl font-bold text-purple-600">{{ $statistics['with_users'] }}</div>
                        <div class="text-sm text-gray-600">With Users</div>
                    </div>
                    <div class="text-center p-4 bg-yellow-50 rounded-lg">
                        <div class="text-3xl font-bold text-yellow-600">{{ $statistics['total_notes'] }}</div>
                        <div class="text-sm text-gray-600">Total Notes</div>
                    </div>
                </div>

                <!-- Form Level Breakdown -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    @if(isset($statistics['form_stats']) && is_array($statistics['form_stats']))
                        @foreach($statistics['form_stats'] as $level => $stats)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">{{ $level }}</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600">{{ $stats['count'] ?? 0 }}</div>
                                    <div class="text-xs text-gray-600">Subjects</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600">{{ $stats['notes'] ?? 0 }}</div>
                                    <div class="text-xs text-gray-600">Notes</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>

                <!-- Category Breakdown -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @if(isset($statistics['category_stats']) && is_array($statistics['category_stats']))
                        @foreach($statistics['category_stats'] as $category => $stats)
                        <div class="text-center p-4 rounded-lg
                            @if($category === 'Core') bg-red-50
                            @elseif($category === 'Science') bg-green-50
                            @elseif($category === 'Arts') bg-yellow-50
                            @elseif($category === 'Technical') bg-purple-50
                            @endif">
                            <div class="text-xl font-bold
                                @if($category === 'Core') text-red-600
                                @elseif($category === 'Science') text-green-600
                                @elseif($category === 'Arts') text-yellow-600
                                @elseif($category === 'Technical') text-purple-600
                                @endif">{{ $stats['count'] ?? 0 }}</div>
                            <div class="text-sm
                                @if($category === 'Core') text-red-600
                                @elseif($category === 'Science') text-green-600
                                @elseif($category === 'Arts') text-yellow-600
                                @elseif($category === 'Technical') text-purple-600
                                @endif">{{ $category }}</div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-submit search form on Enter
    document.getElementById('search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            this.form.submit();
        }
    });
</script>
@endsection
