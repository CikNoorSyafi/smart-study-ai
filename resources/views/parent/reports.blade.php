@extends('layouts.app')

@section('title', 'Performance Reports - Smart Study')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 mb-2">Performance Reports</h1>
                    <p class="text-slate-600">View detailed performance reports and analytics for your children</p>
                </div>
                <a href="{{ route('dashboard') }}" class="bg-slate-600 text-white px-4 py-2 rounded-lg hover:bg-slate-700 transition-colors">
                    ← Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Filter Options -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Filter Reports</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <select class="border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option>All Children</option>
                    <option>John Smith Jr.</option>
                    <option>Emma Smith</option>
                </select>
                <select class="border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option>All Report Types</option>
                    <option>Monthly Report</option>
                    <option>Assessment Report</option>
                    <option>Behavioral Report</option>
                    <option>Conference Report</option>
                </select>
                <select class="border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option>Last 3 Months</option>
                    <option>Last Month</option>
                    <option>Last 6 Months</option>
                    <option>This Year</option>
                </select>
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Apply Filters
                </button>
            </div>
        </div>

        <!-- Reports List -->
        <div class="space-y-6">
            @foreach($reports as $report)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-3">
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">{{ $report['title'] }}</h3>
                                <div class="flex items-center text-sm text-slate-600 mt-1">
                                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs font-medium mr-3">{{ $report['type'] }}</span>
                                    <span>{{ \Carbon\Carbon::parse($report['date'])->format('M j, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h4 class="font-medium text-slate-900 mb-2">Children Included:</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($report['children'] as $child)
                                <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-sm">{{ $child }}</span>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-4">
                            <h4 class="font-medium text-slate-900 mb-2">Summary:</h4>
                            <p class="text-slate-600">{{ $report['summary'] }}</p>
                        </div>

                        <div class="flex items-center">
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($report['status'] === 'completed') bg-green-100 text-green-700
                                @else bg-yellow-100 text-yellow-700 @endif">
                                {{ ucfirst($report['status']) }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 ml-6">
                        @if($report['status'] === 'completed' && $report['download_url'])
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download PDF
                        </button>
                        @endif
                        <button class="bg-slate-600 text-white px-4 py-2 rounded-lg hover:bg-slate-700 transition-colors text-sm">
                            View Details
                        </button>
                        @if($report['status'] === 'pending')
                        <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm">
                            Schedule Meeting
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Report Statistics -->
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-6">Report Statistics</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600 mb-2">{{ count($reports) }}</div>
                    <div class="text-sm text-slate-600">Total Reports</div>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600 mb-2">{{ collect($reports)->where('status', 'completed')->count() }}</div>
                    <div class="text-sm text-slate-600">Completed</div>
                </div>
                <div class="text-center p-4 bg-yellow-50 rounded-lg">
                    <div class="text-2xl font-bold text-yellow-600 mb-2">{{ collect($reports)->where('status', 'pending')->count() }}</div>
                    <div class="text-sm text-slate-600">Pending</div>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600 mb-2">{{ \Carbon\Carbon::parse(collect($reports)->max('date'))->format('M j') }}</div>
                    <div class="text-sm text-slate-600">Latest Report</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('parent.children') }}" class="flex items-center p-4 bg-orange-50 rounded-lg border border-orange-200 hover:bg-orange-100 transition-colors">
                    <div class="w-10 h-10 bg-orange-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-medium text-slate-900">View Children</div>
                        <div class="text-sm text-slate-600">Check individual progress</div>
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
                        <div class="text-sm text-slate-600">Contact teachers</div>
                    </div>
                </a>

                <a href="{{ route('parent.detailed-reports') }}" class="flex items-center p-4 bg-purple-50 rounded-lg border border-purple-200 hover:bg-purple-100 transition-colors">
                    <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-medium text-slate-900">Detailed Analytics</div>
                        <div class="text-sm text-slate-600">Advanced reports</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
