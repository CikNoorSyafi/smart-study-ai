@extends('layouts.admin')

@section('title', 'User Profile Statistics')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Profile Statistics</h1>
                    <p class="mt-1 text-sm text-gray-600">Overview of user profile completion and statistics</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.user-profiles.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-users mr-2"></i>View Profiles
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Total Users -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_users']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Profiles Created -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-id-card text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Profiles Created</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['profiles_created']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Missing Profiles -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-slash text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Missing Profiles</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['profiles_missing']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Complete Profiles -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Complete Profiles</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['complete_profiles']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Incomplete Profiles -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Incomplete Profiles</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['incomplete_profiles']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Completion Rate -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-percentage text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Completion Rate</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['completion_rate'] }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Breakdown -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Profile Status Chart -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Profile Status Breakdown</h3>
                    
                    <!-- Profile Creation Status -->
                    <div class="mb-8">
                        <h4 class="text-sm font-medium text-gray-700 mb-4">Profile Creation</h4>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Profiles Created</span>
                                    <span class="text-gray-900">{{ $stats['profiles_created'] }} / {{ $stats['total_users'] }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $stats['total_users'] > 0 ? round(($stats['profiles_created'] / $stats['total_users']) * 100, 1) : 0 }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $stats['total_users'] > 0 ? round(($stats['profiles_created'] / $stats['total_users']) * 100, 1) : 0 }}% of users have profiles</p>
                            </div>
                            
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Missing Profiles</span>
                                    <span class="text-gray-900">{{ $stats['profiles_missing'] }} / {{ $stats['total_users'] }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-red-600 h-2 rounded-full" style="width: {{ $stats['total_users'] > 0 ? round(($stats['profiles_missing'] / $stats['total_users']) * 100, 1) : 0 }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $stats['total_users'] > 0 ? round(($stats['profiles_missing'] / $stats['total_users']) * 100, 1) : 0 }}% of users need profiles</p>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Completion Status -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-4">Profile Completion</h4>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Complete Profiles</span>
                                    <span class="text-gray-900">{{ $stats['complete_profiles'] }} / {{ $stats['profiles_created'] }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ $stats['completion_rate'] }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $stats['completion_rate'] }}% completion rate</p>
                            </div>
                            
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Incomplete Profiles</span>
                                    <span class="text-gray-900">{{ $stats['incomplete_profiles'] }} / {{ $stats['profiles_created'] }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $stats['profiles_created'] > 0 ? round((100 - $stats['completion_rate']), 1) : 0 }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $stats['profiles_created'] > 0 ? round((100 - $stats['completion_rate']), 1) : 0 }}% need completion</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Quick Actions</h3>
                    
                    <div class="space-y-4">
                        <a href="{{ route('admin.user-profiles.index') }}" 
                           class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                            <i class="fas fa-users mr-3"></i>
                            <div class="text-left">
                                <p class="font-medium">View All Profiles</p>
                                <p class="text-sm text-blue-100">Manage existing user profiles</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.user-profiles.create') }}" 
                           class="w-full bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition-colors flex items-center">
                            <i class="fas fa-plus mr-3"></i>
                            <div class="text-left">
                                <p class="font-medium">Create New Profile</p>
                                <p class="text-sm text-green-100">Add profile for existing user</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.user-profiles.index', ['completion' => 'incomplete']) }}" 
                           class="w-full bg-yellow-600 text-white px-4 py-3 rounded-lg hover:bg-yellow-700 transition-colors flex items-center">
                            <i class="fas fa-exclamation-triangle mr-3"></i>
                            <div class="text-left">
                                <p class="font-medium">Incomplete Profiles</p>
                                <p class="text-sm text-yellow-100">View profiles needing completion</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.users-crud.index') }}" 
                           class="w-full bg-indigo-600 text-white px-4 py-3 rounded-lg hover:bg-indigo-700 transition-colors flex items-center">
                            <i class="fas fa-user-cog mr-3"></i>
                            <div class="text-left">
                                <p class="font-medium">Manage Users</p>
                                <p class="text-sm text-indigo-100">User account management</p>
                            </div>
                        </a>
                    </div>

                    <!-- Summary Stats -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-700 mb-4">Summary</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Users:</span>
                                <span class="text-gray-900 font-medium">{{ number_format($stats['total_users']) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Profile Coverage:</span>
                                <span class="text-gray-900 font-medium">{{ $stats['total_users'] > 0 ? round(($stats['profiles_created'] / $stats['total_users']) * 100, 1) : 0 }}%</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Completion Rate:</span>
                                <span class="text-gray-900 font-medium">{{ $stats['completion_rate'] }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
