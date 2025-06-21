@extends('layouts.admin')

@section('title', 'User Profiles')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">User Profiles</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage user profile information and completion status</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.user-profiles.statistics') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-chart-bar mr-2"></i>Statistics
                    </a>
                    <a href="{{ route('admin.user-profiles.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add Profile
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" action="{{ route('admin.user-profiles.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="Search by name, email, phone..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="completion" class="block text-sm font-medium text-gray-700 mb-2">Completion Status</label>
                    <select name="completion" id="completion" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Profiles</option>
                        <option value="complete" {{ request('completion') === 'complete' ? 'selected' : '' }}>Complete</option>
                        <option value="incomplete" {{ request('completion') === 'incomplete' ? 'selected' : '' }}>Incomplete</option>
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.user-profiles.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Profiles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($profiles as $profile)
            <div class="bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                <div class="p-6">
                    <!-- Profile Header -->
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                            @if($profile->initials)
                                <span class="text-white text-lg font-bold">{{ $profile->initials }}</span>
                            @else
                                <span class="text-white text-lg font-bold">{{ substr($profile->user->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $profile->full_name ?: $profile->user->name }}
                            </h3>
                            <p class="text-sm text-gray-600">{{ $profile->user->email }}</p>
                            <p class="text-xs text-gray-500">{{ $profile->user->role }}</p>
                        </div>
                    </div>

                    <!-- Profile Details -->
                    <div class="space-y-2 mb-4">
                        @if($profile->age)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Age:</span>
                            <span class="text-gray-900">{{ $profile->age }} years</span>
                        </div>
                        @endif
                        @if($profile->phone_number)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Phone:</span>
                            <span class="text-gray-900">{{ $profile->phone_number }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Language:</span>
                            <span class="text-gray-900">{{ strtoupper($profile->preferred_language) }}</span>
                        </div>
                    </div>

                    <!-- Completion Progress -->
                    <div class="mb-4">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-500">Profile Completion</span>
                            <span class="text-gray-900">{{ $profile->getCompletionPercentage() }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $profile->getCompletionPercentage() }}%"></div>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div class="mb-4">
                        @if($profile->isComplete())
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Complete
                            </span>
                        @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Incomplete
                            </span>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.user-profiles.show', $profile) }}" 
                           class="flex-1 bg-blue-600 text-white text-center px-3 py-2 rounded-md text-sm hover:bg-blue-700 transition-colors">
                            <i class="fas fa-eye mr-1"></i>View
                        </a>
                        <a href="{{ route('admin.user-profiles.edit', $profile) }}" 
                           class="flex-1 bg-gray-600 text-white text-center px-3 py-2 rounded-md text-sm hover:bg-gray-700 transition-colors">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <i class="fas fa-user-circle text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No profiles found</h3>
                    <p class="text-gray-500 mb-4">Get started by creating a user profile.</p>
                    <a href="{{ route('admin.user-profiles.create') }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Create Profile
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($profiles->hasPages())
        <div class="mt-8">
            {{ $profiles->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
