@extends('layouts.admin')

@section('title', 'User Profile Details')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Profile Details</h1>
                    <p class="mt-1 text-sm text-gray-600">View detailed user profile information</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.user-profiles.edit', $userProfile) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit Profile
                    </a>
                    <a href="{{ route('admin.users-crud.show', $userProfile->user) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-user mr-2"></i>View User
                    </a>
                    <a href="{{ route('admin.user-profiles.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Profiles
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Overview -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <!-- Profile Header -->
                        <div class="flex items-center space-x-6 mb-8">
                            <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                @if($userProfile->profile_picture_url)
                                    <img src="{{ $userProfile->profile_picture_url }}" alt="Profile Picture" class="w-24 h-24 rounded-full object-cover">
                                @else
                                    <span class="text-3xl font-bold text-white">{{ $stats['initials'] ?: substr($userProfile->user->name, 0, 1) }}</span>
                                @endif
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">{{ $stats['full_name'] ?: $userProfile->user->name }}</h2>
                                <p class="text-gray-600 text-lg">{{ $userProfile->user->email }}</p>
                                <div class="mt-2 flex items-center space-x-4">
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                        @if($userProfile->user->role === 'admin') bg-red-100 text-red-800
                                        @elseif($userProfile->user->role === 'teacher') bg-blue-100 text-blue-800
                                        @elseif($userProfile->user->role === 'student') bg-green-100 text-green-800
                                        @else bg-purple-100 text-purple-800 @endif">
                                        {{ ucfirst($userProfile->user->role) }}
                                    </span>
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                        {{ $stats['is_complete'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $stats['is_complete'] ? 'Complete Profile' : 'Incomplete Profile' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Completion -->
                        <div class="mb-8">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="text-lg font-medium text-gray-900">Profile Completion</h3>
                                <span class="text-sm font-medium text-gray-600">{{ $stats['completion_percentage'] }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" style="width: {{ $stats['completion_percentage'] }}%"></div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                        <dd class="text-sm text-gray-900">{{ $stats['full_name'] ?: 'Not provided' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">First Name</dt>
                                        <dd class="text-sm text-gray-900">{{ $userProfile->first_name ?: 'Not provided' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Last Name</dt>
                                        <dd class="text-sm text-gray-900">{{ $userProfile->last_name ?: 'Not provided' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                                        <dd class="text-sm text-gray-900">
                                            @if($userProfile->date_of_birth)
                                                {{ $userProfile->date_of_birth->format('F j, Y') }}
                                                @if($stats['age'])
                                                    <span class="text-gray-500">({{ $stats['age'] }} years old)</span>
                                                @endif
                                            @else
                                                Not provided
                                            @endif
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                                        <dd class="text-sm text-gray-900">{{ $userProfile->phone_number ?: 'Not provided' }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Preferences</h3>
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Preferred Language</dt>
                                        <dd class="text-sm text-gray-900">{{ strtoupper($userProfile->preferred_language) }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Timezone</dt>
                                        <dd class="text-sm text-gray-900">{{ $userProfile->timezone ?: 'Not set' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Profile Created</dt>
                                        <dd class="text-sm text-gray-900">{{ $userProfile->updated_at->format('M d, Y g:i A') }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                        <dd class="text-sm text-gray-900">{{ $userProfile->updated_at->diffForHumans() }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Bio Section -->
                        @if($userProfile->bio)
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Bio</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-700 leading-relaxed">{{ $userProfile->bio }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('admin.user-profiles.edit', $userProfile) }}" 
                               class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                                <i class="fas fa-edit mr-2"></i>Edit Profile
                            </a>
                            
                            <a href="{{ route('admin.users-crud.show', $userProfile->user) }}" 
                               class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center">
                                <i class="fas fa-user mr-2"></i>View User Account
                            </a>

                            <a href="{{ route('admin.users-crud.edit', $userProfile->user) }}" 
                               class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors flex items-center">
                                <i class="fas fa-user-edit mr-2"></i>Edit User Account
                            </a>

                            <form action="{{ route('admin.user-profiles.destroy', $userProfile) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this profile? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center">
                                    <i class="fas fa-trash mr-2"></i>Delete Profile
                                </button>
                            </form>
                        </div>

                        <!-- Profile Statistics -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-medium text-gray-900 mb-4">Profile Statistics</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Completion Rate</span>
                                    <span class="text-gray-900 font-medium">{{ $stats['completion_percentage'] }}%</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Profile Status</span>
                                    <span class="text-gray-900 font-medium">{{ $stats['is_complete'] ? 'Complete' : 'Incomplete' }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">User Status</span>
                                    <span class="text-gray-900 font-medium">{{ $userProfile->user->is_active ? 'Active' : 'Inactive' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
