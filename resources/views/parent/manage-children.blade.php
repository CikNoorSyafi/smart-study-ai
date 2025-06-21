@extends('layouts.app')

@section('title', 'Manage Children - Smart Study')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 mb-2">Manage Children</h1>
                    <p class="text-slate-600">Update and manage your children's information and settings</p>
                </div>
                <a href="{{ route('dashboard') }}" class="bg-slate-600 text-white px-4 py-2 rounded-lg hover:bg-slate-700 transition-colors">
                    ← Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Children Management -->
        <div class="space-y-6">
            @foreach($children as $child)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-start justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-blue-600 font-bold text-xl">{{ substr($child['name'], 0, 2) }}</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-slate-900">{{ $child['name'] }}</h3>
                            <p class="text-slate-600">{{ $child['grade'] }} • Student ID: {{ $child['student_id'] }}</p>
                            <span class="inline-block px-2 py-1 rounded-full text-xs font-medium
                                @if($child['status'] === 'active') bg-green-100 text-green-700
                                @else bg-yellow-100 text-yellow-700 @endif">
                                {{ ucfirst($child['status']) }}
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                            Edit Info
                        </button>
                        <a href="{{ route('parent.child.progress', $child['id']) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm">
                            View Progress
                        </a>
                    </div>
                </div>

                <!-- Child Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Basic Information -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-slate-900 border-b border-slate-200 pb-2">Basic Information</h4>
                        <div>
                            <label class="text-sm text-slate-600">Full Name</label>
                            <p class="font-medium text-slate-900">{{ $child['name'] }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-slate-600">Grade Level</label>
                            <p class="font-medium text-slate-900">{{ $child['grade'] }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-slate-600">Enrollment Date</label>
                            <p class="font-medium text-slate-900">{{ \Carbon\Carbon::parse($child['enrollment_date'])->format('M j, Y') }}</p>
                        </div>
                    </div>

                    <!-- Contact & Emergency -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-slate-900 border-b border-slate-200 pb-2">Contact & Emergency</h4>
                        <div>
                            <label class="text-sm text-slate-600">Emergency Contact</label>
                            <p class="font-medium text-slate-900">{{ $child['emergency_contact'] }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-slate-600">Medical Notes</label>
                            <p class="font-medium text-slate-900">{{ $child['medical_notes'] }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-slate-600">Transportation</label>
                            <p class="font-medium text-slate-900">{{ $child['transportation'] }}</p>
                        </div>
                    </div>

                    <!-- School Services -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-slate-900 border-b border-slate-200 pb-2">School Services</h4>
                        <div>
                            <label class="text-sm text-slate-600">Lunch Plan</label>
                            <p class="font-medium text-slate-900">{{ $child['lunch_plan'] }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-slate-600">Extracurricular Activities</label>
                            <div class="flex flex-wrap gap-1 mt-1">
                                @foreach($child['extracurricular'] as $activity)
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs">{{ $activity }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions for this child -->
                <div class="mt-6 pt-6 border-t border-slate-200">
                    <h4 class="font-medium text-slate-900 mb-3">Quick Actions</h4>
                    <div class="flex flex-wrap gap-3">
                        <button class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors text-sm">
                            Update Emergency Contact
                        </button>
                        <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors text-sm">
                            Change Transportation
                        </button>
                        <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm">
                            Modify Lunch Plan
                        </button>
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                            Update Medical Info
                        </button>
                        <a href="{{ route('parent.messages') }}" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors text-sm">
                            Contact Teachers
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Family Settings -->
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-6">Family Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-slate-900 mb-4">Notification Preferences</h4>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-slate-700">Email notifications for grades</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-slate-700">SMS alerts for attendance</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-slate-700">Weekly progress reports</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-slate-700">Emergency notifications</span>
                        </label>
                    </div>
                </div>

                <div>
                    <h4 class="font-medium text-slate-900 mb-4">Communication Settings</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm text-slate-600 mb-1">Preferred Contact Method</label>
                            <select class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option>Email</option>
                                <option>Phone</option>
                                <option>SMS</option>
                                <option>School App</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600 mb-1">Best Time to Contact</label>
                            <select class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option>Morning (8AM - 12PM)</option>
                                <option>Afternoon (12PM - 5PM)</option>
                                <option>Evening (5PM - 8PM)</option>
                                <option>Anytime</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-slate-200">
                <div class="flex gap-3">
                    <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Save Settings
                    </button>
                    <button class="bg-slate-600 text-white px-6 py-2 rounded-lg hover:bg-slate-700 transition-colors">
                        Reset to Default
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Navigation -->
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Quick Navigation</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('parent.children') }}" class="flex items-center p-4 bg-orange-50 rounded-lg border border-orange-200 hover:bg-orange-100 transition-colors">
                    <div class="w-10 h-10 bg-orange-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-medium text-slate-900">View Children</div>
                        <div class="text-sm text-slate-600">Progress overview</div>
                    </div>
                </a>

                <a href="{{ route('parent.reports') }}" class="flex items-center p-4 bg-yellow-50 rounded-lg border border-yellow-200 hover:bg-yellow-100 transition-colors">
                    <div class="w-10 h-10 bg-yellow-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-medium text-slate-900">Reports</div>
                        <div class="text-sm text-slate-600">Performance data</div>
                    </div>
                </a>

                <a href="{{ route('parent.messages') }}" class="flex items-center p-4 bg-green-50 rounded-lg border border-green-200 hover:bg-green-100 transition-colors">
                    <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-medium text-slate-900">Messages</div>
                        <div class="text-sm text-slate-600">Communication</div>
                    </div>
                </a>

                <a href="{{ route('parent.detailed-reports') }}" class="flex items-center p-4 bg-purple-50 rounded-lg border border-purple-200 hover:bg-purple-100 transition-colors">
                    <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-medium text-slate-900">Analytics</div>
                        <div class="text-sm text-slate-600">Detailed reports</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
