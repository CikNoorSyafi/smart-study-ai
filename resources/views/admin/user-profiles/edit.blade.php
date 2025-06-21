@extends('layouts.admin')

@section('title', 'Edit User Profile')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit User Profile</h1>
                    <p class="mt-1 text-sm text-gray-600">Update profile information for {{ $userProfile->user->name }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.user-profiles.show', $userProfile) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i>View Profile
                    </a>
                    <a href="{{ route('admin.user-profiles.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Profiles
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow">
            <form action="{{ route('admin.user-profiles.update', $userProfile) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <!-- User Information (Read-only) -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">User Account</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-lg font-bold">{{ substr($userProfile->user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="text-lg font-semibold text-gray-900">{{ $userProfile->user->name }}</p>
                                <p class="text-gray-600">{{ $userProfile->user->email }}</p>
                                <p class="text-sm text-gray-500">{{ ucfirst($userProfile->user->role) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $userProfile->first_name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('first_name') border-red-500 @enderror">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $userProfile->last_name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('last_name') border-red-500 @enderror">
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" 
                                   value="{{ old('date_of_birth', $userProfile->date_of_birth ? $userProfile->date_of_birth->format('Y-m-d') : '') }}"
                                   max="{{ date('Y-m-d') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('date_of_birth') border-red-500 @enderror">
                            @error('date_of_birth')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number', $userProfile->phone_number) }}"
                                   placeholder="+1234567890"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone_number') border-red-500 @enderror">
                            @error('phone_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="preferred_language" class="block text-sm font-medium text-gray-700 mb-2">Preferred Language</label>
                            <select name="preferred_language" id="preferred_language"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('preferred_language') border-red-500 @enderror">
                                <option value="en" {{ old('preferred_language', $userProfile->preferred_language) === 'en' ? 'selected' : '' }}>English</option>
                                <option value="es" {{ old('preferred_language', $userProfile->preferred_language) === 'es' ? 'selected' : '' }}>Spanish</option>
                                <option value="fr" {{ old('preferred_language', $userProfile->preferred_language) === 'fr' ? 'selected' : '' }}>French</option>
                                <option value="de" {{ old('preferred_language', $userProfile->preferred_language) === 'de' ? 'selected' : '' }}>German</option>
                                <option value="it" {{ old('preferred_language', $userProfile->preferred_language) === 'it' ? 'selected' : '' }}>Italian</option>
                                <option value="pt" {{ old('preferred_language', $userProfile->preferred_language) === 'pt' ? 'selected' : '' }}>Portuguese</option>
                                <option value="zh" {{ old('preferred_language', $userProfile->preferred_language) === 'zh' ? 'selected' : '' }}>Chinese</option>
                                <option value="ja" {{ old('preferred_language', $userProfile->preferred_language) === 'ja' ? 'selected' : '' }}>Japanese</option>
                                <option value="ko" {{ old('preferred_language', $userProfile->preferred_language) === 'ko' ? 'selected' : '' }}>Korean</option>
                                <option value="ar" {{ old('preferred_language', $userProfile->preferred_language) === 'ar' ? 'selected' : '' }}>Arabic</option>
                            </select>
                            @error('preferred_language')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                            <select name="timezone" id="timezone"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('timezone') border-red-500 @enderror">
                                <option value="">Select Timezone</option>
                                <option value="UTC" {{ old('timezone', $userProfile->timezone) === 'UTC' ? 'selected' : '' }}>UTC</option>
                                <option value="America/New_York" {{ old('timezone', $userProfile->timezone) === 'America/New_York' ? 'selected' : '' }}>Eastern Time (US)</option>
                                <option value="America/Chicago" {{ old('timezone', $userProfile->timezone) === 'America/Chicago' ? 'selected' : '' }}>Central Time (US)</option>
                                <option value="America/Denver" {{ old('timezone', $userProfile->timezone) === 'America/Denver' ? 'selected' : '' }}>Mountain Time (US)</option>
                                <option value="America/Los_Angeles" {{ old('timezone', $userProfile->timezone) === 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time (US)</option>
                                <option value="Europe/London" {{ old('timezone', $userProfile->timezone) === 'Europe/London' ? 'selected' : '' }}>London</option>
                                <option value="Europe/Paris" {{ old('timezone', $userProfile->timezone) === 'Europe/Paris' ? 'selected' : '' }}>Paris</option>
                                <option value="Europe/Berlin" {{ old('timezone', $userProfile->timezone) === 'Europe/Berlin' ? 'selected' : '' }}>Berlin</option>
                                <option value="Asia/Tokyo" {{ old('timezone', $userProfile->timezone) === 'Asia/Tokyo' ? 'selected' : '' }}>Tokyo</option>
                                <option value="Asia/Shanghai" {{ old('timezone', $userProfile->timezone) === 'Asia/Shanghai' ? 'selected' : '' }}>Shanghai</option>
                                <option value="Australia/Sydney" {{ old('timezone', $userProfile->timezone) === 'Australia/Sydney' ? 'selected' : '' }}>Sydney</option>
                            </select>
                            @error('timezone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Profile Picture and Bio -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Profile Details</h3>
                    <div class="space-y-6">
                        <div>
                            <label for="profile_picture_url" class="block text-sm font-medium text-gray-700 mb-2">Profile Picture URL</label>
                            <input type="url" name="profile_picture_url" id="profile_picture_url" 
                                   value="{{ old('profile_picture_url', $userProfile->profile_picture_url) }}"
                                   placeholder="https://example.com/profile.jpg"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('profile_picture_url') border-red-500 @enderror">
                            @error('profile_picture_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @if($userProfile->profile_picture_url)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-2">Current profile picture:</p>
                                    <img src="{{ $userProfile->profile_picture_url }}" alt="Current Profile Picture" class="w-16 h-16 rounded-full object-cover">
                                </div>
                            @endif
                        </div>

                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                            <textarea name="bio" id="bio" rows="4" 
                                      placeholder="Tell us about yourself..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('bio') border-red-500 @enderror">{{ old('bio', $userProfile->bio) }}</textarea>
                            @error('bio')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Maximum 1000 characters</p>
                        </div>
                    </div>
                </div>

                <!-- Current Profile Status -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Profile Status</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Completion Status</p>
                                <p class="text-sm text-gray-600">{{ $userProfile->isComplete() ? 'Profile is complete' : 'Profile needs more information' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-blue-600">{{ $userProfile->getCompletionPercentage() }}%</p>
                                <p class="text-sm text-gray-500">Complete</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $userProfile->getCompletionPercentage() }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.user-profiles.show', $userProfile) }}" 
                       class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
