@extends('layouts.admin')

@section('title', 'Create User Profile')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create User Profile</h1>
                    <p class="mt-1 text-sm text-gray-600">Add detailed profile information for a user</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.user-profiles.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Profiles
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow">
            <form action="{{ route('admin.user-profiles.store') }}" method="POST" class="p-6">
                @csrf
                
                <!-- User Selection -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Select User</h3>
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">User *</label>
                        <select name="user_id" id="user_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('user_id') border-red-500 @enderror">
                            <option value="">Select a user without profile</option>
                            @foreach($users as $user)
                            <option value="{{ $user->user_id }}" {{ old('user_id') === $user->user_id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }}) - {{ ucfirst($user->role) }}
                            </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if($users->isEmpty())
                            <p class="mt-1 text-sm text-yellow-600">All users already have profiles. <a href="{{ route('admin.users-crud.create') }}" class="text-blue-600 hover:text-blue-800">Create a new user first</a>.</p>
                        @endif
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('first_name') border-red-500 @enderror">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('last_name') border-red-500 @enderror">
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}"
                                   max="{{ date('Y-m-d') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('date_of_birth') border-red-500 @enderror">
                            @error('date_of_birth')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
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
                                <option value="en" {{ old('preferred_language', 'en') === 'en' ? 'selected' : '' }}>English</option>
                                <option value="es" {{ old('preferred_language') === 'es' ? 'selected' : '' }}>Spanish</option>
                                <option value="fr" {{ old('preferred_language') === 'fr' ? 'selected' : '' }}>French</option>
                                <option value="de" {{ old('preferred_language') === 'de' ? 'selected' : '' }}>German</option>
                                <option value="it" {{ old('preferred_language') === 'it' ? 'selected' : '' }}>Italian</option>
                                <option value="pt" {{ old('preferred_language') === 'pt' ? 'selected' : '' }}>Portuguese</option>
                                <option value="zh" {{ old('preferred_language') === 'zh' ? 'selected' : '' }}>Chinese</option>
                                <option value="ja" {{ old('preferred_language') === 'ja' ? 'selected' : '' }}>Japanese</option>
                                <option value="ko" {{ old('preferred_language') === 'ko' ? 'selected' : '' }}>Korean</option>
                                <option value="ar" {{ old('preferred_language') === 'ar' ? 'selected' : '' }}>Arabic</option>
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
                                <option value="UTC" {{ old('timezone') === 'UTC' ? 'selected' : '' }}>UTC</option>
                                <option value="America/New_York" {{ old('timezone') === 'America/New_York' ? 'selected' : '' }}>Eastern Time (US)</option>
                                <option value="America/Chicago" {{ old('timezone') === 'America/Chicago' ? 'selected' : '' }}>Central Time (US)</option>
                                <option value="America/Denver" {{ old('timezone') === 'America/Denver' ? 'selected' : '' }}>Mountain Time (US)</option>
                                <option value="America/Los_Angeles" {{ old('timezone') === 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time (US)</option>
                                <option value="Europe/London" {{ old('timezone') === 'Europe/London' ? 'selected' : '' }}>London</option>
                                <option value="Europe/Paris" {{ old('timezone') === 'Europe/Paris' ? 'selected' : '' }}>Paris</option>
                                <option value="Europe/Berlin" {{ old('timezone') === 'Europe/Berlin' ? 'selected' : '' }}>Berlin</option>
                                <option value="Asia/Tokyo" {{ old('timezone') === 'Asia/Tokyo' ? 'selected' : '' }}>Tokyo</option>
                                <option value="Asia/Shanghai" {{ old('timezone') === 'Asia/Shanghai' ? 'selected' : '' }}>Shanghai</option>
                                <option value="Australia/Sydney" {{ old('timezone') === 'Australia/Sydney' ? 'selected' : '' }}>Sydney</option>
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
                            <input type="url" name="profile_picture_url" id="profile_picture_url" value="{{ old('profile_picture_url') }}"
                                   placeholder="https://example.com/profile.jpg"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('profile_picture_url') border-red-500 @enderror">
                            @error('profile_picture_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                            <textarea name="bio" id="bio" rows="4" 
                                      placeholder="Tell us about yourself..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('bio') border-red-500 @enderror">{{ old('bio') }}</textarea>
                            @error('bio')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Maximum 1000 characters</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.user-profiles.index') }}" 
                       class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Create Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
