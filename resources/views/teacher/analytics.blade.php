@extends('teacher.layout')

@section('title', 'Analytics')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Analytics & Reports</h1>
        <p class="mt-1 text-sm text-gray-600">Track your teaching performance and student progress.</p>
    </div>
    <div class="flex space-x-3">
        <select class="border border-gray-300 rounded-md px-3 py-2 text-sm">
            <option>Last 30 days</option>
            <option>Last 3 months</option>
            <option>Last 6 months</option>
            <option>This year</option>
        </select>
        <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
            <i class="fas fa-download mr-2"></i>Export Report
        </button>
    </div>
</div>
@endsection

@section('content')
<!-- Key Metrics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-users text-white"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Active Students</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $subjectStats->sum('student_count') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <span class="text-green-600 font-medium">+12%</span>
                <span class="text-gray-500">from last month</span>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Content Created</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $subjectStats->sum('notes_count') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <span class="text-green-600 font-medium">+8%</span>
                <span class="text-gray-500">from last month</span>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Avg. Progress</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ rand(75, 90) }}%</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <span class="text-green-600 font-medium">+5%</span>
                <span class="text-gray-500">from last month</span>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-star text-white"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Satisfaction</dt>
                        <dd class="text-lg font-medium text-gray-900">4.{{ rand(6, 9) }}/5</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <span class="text-green-600 font-medium">+0.2</span>
                <span class="text-gray-500">from last month</span>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Content Creation Chart -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Monthly Content Creation</h3>
        </div>
        <div class="p-6">
            <canvas id="contentChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Subject Performance -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Subject Performance</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($subjectStats as $subject)
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex justify-between text-sm font-medium text-gray-900 mb-1">
                            <span>{{ $subject->name }}</span>
                            <span>{{ $subject->student_count }} students</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            @php $performance = rand(60, 95); @endphp
                            <div class="bg-primary h-2 rounded-full" style="width: {{ $performance }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span>{{ $subject->notes_count }} notes</span>
                            <span>{{ $performance }}% avg. progress</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Detailed Analytics -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Student Engagement -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Student Engagement</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Daily Active Students</span>
                    <span class="font-medium text-gray-900">{{ rand(15, 25) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Weekly Active Students</span>
                    <span class="font-medium text-gray-900">{{ rand(35, 45) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Avg. Session Duration</span>
                    <span class="font-medium text-gray-900">{{ rand(15, 35) }}m</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Questions Answered</span>
                    <span class="font-medium text-gray-900">{{ rand(150, 300) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Completion Rate</span>
                    <span class="font-medium text-green-600">{{ rand(75, 90) }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performing Content -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Top Performing Content</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @php 
                $topContent = [
                    ['title' => 'Introduction to Algebra', 'views' => rand(150, 300), 'rating' => rand(40, 50)/10],
                    ['title' => 'Basic Geometry Concepts', 'views' => rand(100, 250), 'rating' => rand(40, 50)/10],
                    ['title' => 'Fractions and Decimals', 'views' => rand(80, 200), 'rating' => rand(40, 50)/10],
                    ['title' => 'Word Problems Practice', 'views' => rand(60, 150), 'rating' => rand(40, 50)/10],
                ];
                @endphp
                @foreach($topContent as $content)
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $content['title'] }}</p>
                        <div class="flex items-center mt-1">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-xs {{ $i <= floor($content['rating']) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                            <span class="ml-2 text-xs text-gray-500">{{ $content['views'] }} views</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Feedback -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Feedback</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @php 
                $feedback = [
                    ['student' => 'Alice Johnson', 'comment' => 'Great explanation of complex topics!', 'rating' => 5, 'time' => '2 hours ago'],
                    ['student' => 'Bob Smith', 'comment' => 'Very helpful practice questions.', 'rating' => 4, 'time' => '1 day ago'],
                    ['student' => 'Carol Davis', 'comment' => 'Could use more examples.', 'rating' => 3, 'time' => '2 days ago'],
                ];
                @endphp
                @foreach($feedback as $item)
                <div class="border-l-4 border-blue-400 pl-4">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-sm font-medium text-gray-900">{{ $item['student'] }}</p>
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star text-xs {{ $i <= $item['rating'] ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-1">{{ $item['comment'] }}</p>
                    <p class="text-xs text-gray-500">{{ $item['time'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Content Creation Chart
const ctx = document.getElementById('contentChart').getContext('2d');
const contentChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Notes Created',
            data: @json(array_values($monthlyNotes)),
            borderColor: '#3B82F6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
@endsection
