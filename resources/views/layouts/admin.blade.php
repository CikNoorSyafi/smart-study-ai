<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Smart Study</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-transition { transition: transform 0.3s ease-in-out; }

        /* Ensure proper sidebar layout */
        .admin-sidebar {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Smooth scrolling for navigation */
        .admin-nav {
            scrollbar-width: thin;
            scrollbar-color: #e5e7eb #f9fafb;
        }

        .admin-nav::-webkit-scrollbar {
            width: 6px;
        }

        .admin-nav::-webkit-scrollbar-track {
            background: #f9fafb;
        }

        .admin-nav::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 3px;
        }

        .admin-nav::-webkit-scrollbar-thumb:hover {
            background: #d1d5db;
        }
    </style>
</head>
<body class="bg-gray-50" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform lg:translate-x-0 lg:static lg:inset-0 sidebar-transition admin-sidebar"
             :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }"
             x-cloak>

            <!-- Sidebar Header -->
            <div class="flex items-center justify-between h-16 px-6 bg-gradient-to-r from-blue-600 to-indigo-700 shadow-lg flex-shrink-0">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-graduation-cap text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-white text-lg font-bold">Smart Study</h1>
                        <p class="text-blue-100 text-xs">Admin Panel</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-white hover:text-blue-200 transition-colors duration-200">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 mt-6 px-3 overflow-y-auto admin-nav">
                <!-- Dashboard Section -->
                <div class="mb-8">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Overview</h3>
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i>
                        <span>Main Dashboard</span>
                    </a>
                    <a href="{{ route('admin.analytics') }}"
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.analytics') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-chart-line mr-3 w-5 text-center"></i>
                        <span>Analytics</span>
                    </a>
                </div>

                <!-- User Management Section -->
                <div class="mb-8">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">User Management</h3>
                    <a href="{{ route('admin.users-crud.index') }}"
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users-crud.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-users mr-3 w-5 text-center"></i>
                        <span class="flex-1">Users</span>
                        <span class="ml-2 bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-medium">{{ \App\Models\User::count() }}</span>
                    </a>
                    <a href="{{ route('admin.user-profiles.index') }}"
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.user-profiles.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-id-card mr-3 w-5 text-center"></i>
                        <span>Profiles</span>
                    </a>
                </div>

                <!-- Content Management Section -->
                <div class="mb-8">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Content Management</h3>
                    <a href="{{ route('admin.subjects.index') }}"
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.subjects.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-book mr-3 w-5 text-center"></i>
                        <span class="flex-1">Subjects</span>
                        <span class="ml-2 bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-medium">{{ \App\Models\Subject::count() }}</span>
                    </a>
                    <a href="{{ route('admin.notes-crud.index') }}"
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.notes-crud.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-sticky-note mr-3 w-5 text-center"></i>
                        <span class="flex-1">Notes</span>
                        <span class="ml-2 bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full font-medium">{{ \App\Models\Note::count() }}</span>
                    </a>
                    @if(in_array(session('user.role'), ['admin', 'teacher']))
                    <a href="{{ route('pdf-upload.index') }}"
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('pdf-upload.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-file-pdf mr-3 w-5 text-center"></i>
                        <span class="flex-1">PDF Upload</span>
                        <span class="ml-2 bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full font-medium">{{ \App\Models\Note::whereNotNull('file_path')->count() }}</span>
                    </a>
                    @endif
                </div>

                <!-- Q&A System Section -->
                <div class="mb-8">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Q&A System</h3>
                    <a href="{{ route('admin.questions.index') }}"
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.questions.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-question-circle mr-3 w-5 text-center"></i>
                        <span class="flex-1">Questions</span>
                        <span class="ml-2 bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full font-medium">{{ \App\Models\Question::count() }}</span>
                    </a>
                    <a href="{{ route('admin.answers.index') }}"
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.answers.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-check-circle mr-3 w-5 text-center"></i>
                        <span class="flex-1">Answers</span>
                        <span class="ml-2 bg-emerald-100 text-emerald-800 text-xs px-2 py-1 rounded-full font-medium">{{ \App\Models\Answer::count() }}</span>
                    </a>
                    <a href="{{ route('admin.feedback.index') }}"
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.feedback.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-comments mr-3 w-5 text-center"></i>
                        <span class="flex-1">Feedback</span>
                        <span class="ml-2 bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full font-medium">{{ \App\Models\Feedback::count() }}</span>
                    </a>
                </div>

                <!-- AI Tools Section (Admin & Teacher Only) -->
                @if(in_array(session('user.role'), ['admin', 'teacher']))
                <div class="mb-8">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">AI Tools</h3>
                    <a href="{{ route('pdf-upload.index') }}"
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('pdf-upload.index') || request()->routeIs('pdf-upload.create') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-magic mr-3 w-5 text-center"></i>
                        <span>Question Generator</span>
                    </a>
                    <a href="{{ route('pdf-upload.list') }}"
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('pdf-upload.list') || request()->routeIs('pdf-upload.result') || request()->routeIs('pdf-upload.show') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-file-pdf mr-3 w-5 text-center"></i>
                        <span class="flex-1">PDF Library</span>
                        <span class="ml-2 bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full font-medium">{{ \App\Models\Note::whereNotNull('file_path')->count() }}</span>
                    </a>
                </div>
                @endif

                <!-- System & Tools Section -->
                <div class="mb-6">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">System & Tools</h3>
                    <a href="{{ route('admin.system-health') }}"
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.system-health') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-heartbeat mr-3 w-5 text-center"></i>
                        <span>System Health</span>
                    </a>
                    <a href="{{ route('admin.reports') }}"
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reports') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-chart-bar mr-3 w-5 text-center"></i>
                        <span>Reports</span>
                    </a>
                    <a href="{{ route('admin.export-data') }}"
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-gray-700 hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-download mr-3 w-5 text-center"></i>
                        <span>Export Data</span>
                    </a>
                    <a href="{{ route('admin.settings') }}"
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.settings') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-cog mr-3 w-5 text-center"></i>
                        <span>Settings</span>
                    </a>
                </div>
            </nav>

            <!-- User Info at Bottom -->
            <div class="flex-shrink-0 p-4 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-blue-50">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-md">
                        <span class="text-sm font-bold text-white">{{ substr(session('user.name', 'A'), 0, 1) }}</span>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-semibold text-gray-800">{{ session('user.name', 'Admin User') }}</p>
                        <p class="text-xs text-gray-600 flex items-center">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                            Administrator
                        </p>
                    </div>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-white rounded-lg transition-all duration-200">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute bottom-full right-0 mb-2 w-52 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Quick Actions</p>
                            </div>
                            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors duration-200">
                                <i class="fas fa-home mr-3 w-4 text-center"></i>User Dashboard
                            </a>
                            <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors duration-200">
                                <i class="fas fa-cog mr-3 w-4 text-center"></i>Admin Settings
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form action="{{ route('logout') }}" method="POST" class="block">
                                @csrf
                                <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200">
                                    <i class="fas fa-sign-out-alt mr-3 w-4 text-center"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-0">
            <!-- Top Navigation Bar (Mobile) -->
            <header class="bg-white shadow-md border-b border-gray-200 lg:hidden">
                <div class="flex items-center justify-between h-16 px-4">
                    <button @click="sidebarOpen = true" class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-200">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <div class="flex items-center">
                        <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-md flex items-center justify-center mr-2">
                            <i class="fas fa-graduation-cap text-white text-xs"></i>
                        </div>
                        <h1 class="text-lg font-bold text-gray-900">@yield('page-title', 'Admin Panel')</h1>
                    </div>
                    <div class="flex items-center space-x-3">
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                        <span class="text-xs font-bold text-white">{{ substr(session('user.name', 'A'), 0, 1) }}</span>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mx-4 mt-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times cursor-pointer"></i>
                </span>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-4 mt-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times cursor-pointer"></i>
                </span>
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-4 mt-4" role="alert">
                <div class="font-bold">Please fix the following errors:</div>
                <ul class="mt-2">
                    @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                    @endforeach
                </ul>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times cursor-pointer"></i>
                </span>
            </div>
            @endif

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 px-4 py-3">
                <div class="flex justify-between items-center text-sm text-gray-500">
                    <div>
                        © {{ date('Y') }} Smart Study. All rights reserved.
                    </div>
                    <div class="flex space-x-4">
                        <a href="#" class="hover:text-gray-700">Documentation</a>
                        <a href="#" class="hover:text-gray-700">Support</a>
                        <a href="#" class="hover:text-gray-700">API</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Overlay for mobile sidebar -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden" x-cloak></div>

    <!-- Scripts -->
    <script>
        // Auto-hide flash messages after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                alert.style.display = 'none';
            });
        }, 5000);
    </script>

    @yield('scripts')
</body>
</html>
