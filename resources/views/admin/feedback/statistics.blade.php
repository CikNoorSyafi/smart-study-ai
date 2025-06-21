@extends('layouts.admin')

@section('title', 'Feedback Statistics')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Feedback Statistics</h1>
                    <p class="mt-1 text-sm text-gray-600">Overview of user feedback and ratings</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.feedback.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-list mr-2"></i>View Feedback
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Overview Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Feedback -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-comments text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Feedback</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Positive Feedback -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-thumbs-up text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Positive Feedback</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['positive']) }}</p>
                        <p class="text-xs text-gray-500">4-5 stars</p>
                    </div>
                </div>
            </div>

            <!-- Negative Feedback -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-thumbs-down text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Negative Feedback</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['negative']) }}</p>
                        <p class="text-xs text-gray-500">1-2 stars</p>
                    </div>
                </div>
            </div>

            <!-- Average Rating -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-star text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Average Rating</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['average_rating'] ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">out of 5.0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Statistics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Rating Distribution -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Rating Distribution</h3>
                    
                    @if($stats['by_rating']->count() > 0)
                    <div class="space-y-4">
                        @for($rating = 5; $rating >= 1; $rating--)
                            @php
                                $ratingData = $stats['by_rating']->firstWhere('rating', $rating);
                                $count = $ratingData ? $ratingData->count : 0;
                                $percentage = $stats['total'] > 0 ? round(($count / $stats['total']) * 100, 1) : 0;
                            @endphp
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= $rating; $i++)
                                            <i class="fas fa-star text-yellow-500 text-sm"></i>
                                        @endfor
                                        @for($i = $rating + 1; $i <= 5; $i++)
                                            <i class="far fa-star text-gray-300 text-sm"></i>
                                        @endfor
                                        <span class="ml-2 text-sm text-gray-600">{{ $rating }} Star{{ $rating > 1 ? 's' : '' }}</span>
                                    </div>
                                    <div class="text-sm text-gray-900">
                                        <span class="font-medium">{{ $count }}</span>
                                        <span class="text-gray-500">({{ $percentage }}%)</span>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-500 h-2 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endfor
                    </div>
                    @else
                    <div class="text-center py-8">
                        <i class="fas fa-chart-bar text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">No feedback data available</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Feedback Breakdown -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Feedback Breakdown</h3>
                    
                    @if($stats['total'] > 0)
                    <div class="space-y-6">
                        <!-- Positive vs Negative -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-4">Sentiment Analysis</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Positive (4-5 stars)</span>
                                    <span class="text-sm font-medium text-green-600">{{ round(($stats['positive'] / $stats['total']) * 100, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ round(($stats['positive'] / $stats['total']) * 100, 1) }}%"></div>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Neutral (3 stars)</span>
                                    @php
                                        $neutral = $stats['total'] - $stats['positive'] - $stats['negative'];
                                        $neutralPercentage = round(($neutral / $stats['total']) * 100, 1);
                                    @endphp
                                    <span class="text-sm font-medium text-yellow-600">{{ $neutralPercentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $neutralPercentage }}%"></div>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Negative (1-2 stars)</span>
                                    <span class="text-sm font-medium text-red-600">{{ round(($stats['negative'] / $stats['total']) * 100, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-red-500 h-2 rounded-full" style="width: {{ round(($stats['negative'] / $stats['total']) * 100, 1) }}%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Summary Stats -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-4">Summary</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Responses:</span>
                                    <span class="text-gray-900 font-medium">{{ number_format($stats['total']) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Average Rating:</span>
                                    <span class="text-gray-900 font-medium">{{ $stats['average_rating'] ?? 'N/A' }}/5.0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Satisfaction Rate:</span>
                                    <span class="text-gray-900 font-medium">{{ round(($stats['positive'] / $stats['total']) * 100, 1) }}%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Response Quality:</span>
                                    <span class="text-gray-900 font-medium">
                                        @if($stats['average_rating'] >= 4.5)
                                            Excellent
                                        @elseif($stats['average_rating'] >= 4.0)
                                            Very Good
                                        @elseif($stats['average_rating'] >= 3.5)
                                            Good
                                        @elseif($stats['average_rating'] >= 3.0)
                                            Fair
                                        @else
                                            Needs Improvement
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-8">
                        <i class="fas fa-chart-pie text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">No feedback data available</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 bg-white rounded-lg shadow">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('admin.feedback.index') }}" 
                       class="bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                        <i class="fas fa-list mr-3"></i>
                        <div>
                            <p class="font-medium">View All Feedback</p>
                            <p class="text-sm text-blue-100">Browse and manage feedback</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.feedback.index', ['type' => 'negative']) }}" 
                       class="bg-red-600 text-white px-4 py-3 rounded-lg hover:bg-red-700 transition-colors flex items-center">
                        <i class="fas fa-exclamation-triangle mr-3"></i>
                        <div>
                            <p class="font-medium">Review Negative Feedback</p>
                            <p class="text-sm text-red-100">Address concerns and issues</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.feedback.create') }}" 
                       class="bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition-colors flex items-center">
                        <i class="fas fa-plus mr-3"></i>
                        <div>
                            <p class="font-medium">Add New Feedback</p>
                            <p class="text-sm text-green-100">Create feedback entry</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
