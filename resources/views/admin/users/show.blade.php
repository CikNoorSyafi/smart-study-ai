@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
                    <p class="mt-1 text-sm text-gray-600">View user information and activity</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.users-crud.edit', $user) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit User
                    </a>
                    <a href="{{ route('admin.users-crud.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- User Information Card -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <div class="flex items-center space-x-6 mb-6">
                            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-2xl font-bold text-blue-600">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                                <p class="text-gray-600">{{ $user->email }}</p>
                                <p class="text-sm text-gray-500">@{{ $user->username }}</p>
                                <div class="mt-2">
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                        @if($user->role === 'admin') bg-red-100 text-red-800
                                        @elseif($user->role === 'teacher') bg-blue-100 text-blue-800
                                        @elseif($user->role === 'student') bg-green-100 text-green-800
                                        @else bg-purple-100 text-purple-800 @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                    <span class="ml-2 inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                        {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- User Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Account Information</h3>
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">User ID</dt>
                                        <dd class="text-sm text-gray-900 font-mono">{{ $user->user_id }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Email Verified</dt>
                                        <dd class="text-sm text-gray-900">
                                            @if($user->email_verified_at)
                                                <span class="text-green-600">✓ Verified on {{ $user->email_verified_at->format('M d, Y') }}</span>
                                            @else
                                                <span class="text-red-600">✗ Not verified</span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                                        <dd class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                        <dd class="text-sm text-gray-900">{{ $user->updated_at->format('M d, Y g:i A') }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Activity Statistics</h3>
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Notes Created</dt>
                                        <dd class="text-sm text-gray-900">{{ $stats['notes_count'] }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Questions Generated</dt>
                                        <dd class="text-sm text-gray-900">{{ $stats['questions_count'] }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Feedback Given</dt>
                                        <dd class="text-sm text-gray-900">{{ $stats['feedback_count'] }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Subjects Enrolled</dt>
                                        <dd class="text-sm text-gray-900">{{ $stats['subjects_count'] }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Subjects -->
                        @if($user->subjects->count() > 0)
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Enrolled Subjects</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($user->subjects as $subject)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900">{{ $subject->name }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $subject->description }}</p>
                                    <div class="mt-2">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ ucfirst($subject->pivot->role_in_subject ?? 'student') }}
                                        </span>
                                        <span class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ ucfirst($subject->pivot->level ?? 'beginner') }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
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
                            <a href="{{ route('admin.users-crud.edit', $user) }}" 
                               class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                                <i class="fas fa-edit mr-2"></i>Edit User
                            </a>
                            
                            <form action="{{ route('admin.users-crud.toggle-status', $user) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="w-full bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors flex items-center">
                                    <i class="fas fa-toggle-{{ $user->is_active ? 'off' : 'on' }} mr-2"></i>
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }} User
                                </button>
                            </form>

                            @if($user->role !== 'admin' || \App\Models\User::where('role', 'admin')->count() > 1)
                            <form action="{{ route('admin.users-crud.destroy', $user) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center">
                                    <i class="fas fa-trash mr-2"></i>Delete User
                                </button>
                            </form>
                            @endif
                        </div>

                        <!-- User Profile Link -->
                        @if($user->profile)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Profile Information</h4>
                            <a href="{{ route('admin.user-profiles.show', $user->profile) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                View Full Profile →
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
