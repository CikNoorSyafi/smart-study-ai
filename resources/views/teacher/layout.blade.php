<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Teacher Dashboard') - QuestionCraft</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#10B981',
                        accent: '#F59E0B',
                        danger: '#EF4444',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-graduation-cap text-2xl text-primary mr-2"></i>
                        <span class="text-xl font-bold text-gray-900">QuestionCraft</span>
                        <span class="ml-2 px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Teacher</span>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:ml-8 md:flex md:space-x-8">
                        <a href="{{ route('teacher.dashboard') }}" 
                           class="@if(request()->routeIs('teacher.dashboard*')) border-primary text-primary @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Dashboard
                        </a>
                        <a href="{{ route('teacher.subjects') }}" 
                           class="@if(request()->routeIs('teacher.subjects*')) border-primary text-primary @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-book mr-2"></i>
                            My Subjects
                        </a>
                        <a href="{{ route('teacher.students') }}" 
                           class="@if(request()->routeIs('teacher.students*')) border-primary text-primary @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-users mr-2"></i>
                            Students
                        </a>
                        <a href="{{ route('teacher.content') }}" 
                           class="@if(request()->routeIs('teacher.content*')) border-primary text-primary @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-file-alt mr-2"></i>
                            Content
                        </a>
                        <a href="{{ route('teacher.analytics') }}" 
                           class="@if(request()->routeIs('teacher.analytics*')) border-primary text-primary @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Analytics
                        </a>
                    </div>
                </div>

                <!-- Right side -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <div class="relative">
                        <button id="notifications-button"
                                class="text-gray-400 hover:text-gray-500 relative focus:outline-none"
                                onclick="toggleNotifications()">
                            <i class="fas fa-bell text-lg"></i>
                            <span id="notification-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center hidden">0</span>
                        </button>

                        <!-- Notifications Dropdown -->
                        <div id="notifications-dropdown"
                             class="hidden absolute right-0 mt-2 w-96 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                            <div class="py-1">
                                <!-- Header -->
                                <div class="px-4 py-3 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-medium text-gray-900">Notifications</h3>
                                        <button onclick="markAllAsRead()"
                                                class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                            Mark all as read
                                        </button>
                                    </div>
                                </div>

                                <!-- Notifications List -->
                                <div id="notifications-list" class="max-h-96 overflow-y-auto">
                                    <div class="flex items-center justify-center p-8">
                                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary"></div>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                                    <a href="{{ route('teacher.notifications.get') }}"
                                       class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                        View all notifications
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="relative">
                        <button class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" id="user-menu-button">
                            <img class="h-8 w-8 rounded-full" src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=3B82F6&color=fff' }}" alt="{{ auth()->user()->name }}">
                            <span class="ml-2 text-gray-700 font-medium">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down ml-1 text-gray-400"></i>
                        </button>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500" id="mobile-menu-button">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-gray-50">
                <a href="{{ route('teacher.dashboard') }}" class="@if(request()->routeIs('teacher.dashboard*')) bg-primary text-white @else text-gray-600 hover:bg-gray-100 @endif block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="{{ route('teacher.subjects') }}" class="@if(request()->routeIs('teacher.subjects*')) bg-primary text-white @else text-gray-600 hover:bg-gray-100 @endif block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-book mr-2"></i> My Subjects
                </a>
                <a href="{{ route('teacher.students') }}" class="@if(request()->routeIs('teacher.students*')) bg-primary text-white @else text-gray-600 hover:bg-gray-100 @endif block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-users mr-2"></i> Students
                </a>
                <a href="{{ route('teacher.content') }}" class="@if(request()->routeIs('teacher.content*')) bg-primary text-white @else text-gray-600 hover:bg-gray-100 @endif block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-file-alt mr-2"></i> Content
                </a>
                <a href="{{ route('teacher.analytics') }}" class="@if(request()->routeIs('teacher.analytics*')) bg-primary text-white @else text-gray-600 hover:bg-gray-100 @endif block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-chart-bar mr-2"></i> Analytics
                </a>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    @hasSection('header')
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            @yield('header')
        </div>
    </header>
    @endif

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Flash Messages -->
        @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <p class="text-gray-500 text-sm">&copy; 2024 QuestionCraft. All rights reserved.</p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-question-circle"></i> Help
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });

        // Auto-hide flash messages
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
    </script>

    @stack('scripts')
    @yield('scripts')

    <!-- Notification System JavaScript -->
    <script>
        let notificationsDropdown = null;
        let notificationsBadge = null;
        let notificationsList = null;

        document.addEventListener('DOMContentLoaded', function() {
            notificationsDropdown = document.getElementById('notifications-dropdown');
            notificationsBadge = document.getElementById('notification-badge');
            notificationsList = document.getElementById('notifications-list');

            // Load notifications on page load
            loadNotifications();

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const notificationsButton = document.getElementById('notifications-button');
                if (!notificationsButton.contains(event.target) && !notificationsDropdown.contains(event.target)) {
                    notificationsDropdown.classList.add('hidden');
                }
            });
        });

        function toggleNotifications() {
            if (notificationsDropdown.classList.contains('hidden')) {
                notificationsDropdown.classList.remove('hidden');
                loadNotifications();
            } else {
                notificationsDropdown.classList.add('hidden');
            }
        }

        function loadNotifications() {
            fetch('{{ route("teacher.notifications.get") }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                notificationsList.innerHTML = data.html;
                updateNotificationBadge(data.unread_count);
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                notificationsList.innerHTML = '<div class="p-4 text-center text-red-500">Error loading notifications</div>';
            });
        }

        function updateNotificationBadge(count) {
            if (count > 0) {
                notificationsBadge.textContent = count;
                notificationsBadge.classList.remove('hidden');
            } else {
                notificationsBadge.classList.add('hidden');
            }
        }

        function markAsRead(notificationId) {
            fetch(`/teacher/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateNotificationBadge(data.unread_count);
                    loadNotifications(); // Reload to update UI
                }
            })
            .catch(error => console.error('Error marking notification as read:', error));
        }

        function markAllAsRead() {
            fetch('{{ route("teacher.notifications.mark-all-read") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateNotificationBadge(0);
                    loadNotifications(); // Reload to update UI
                }
            })
            .catch(error => console.error('Error marking all notifications as read:', error));
        }

        function deleteNotification(notificationId) {
            if (confirm('Are you sure you want to delete this notification?')) {
                fetch(`/teacher/notifications/${notificationId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateNotificationBadge(data.unread_count);
                        loadNotifications(); // Reload to update UI
                    }
                })
                .catch(error => console.error('Error deleting notification:', error));
            }
        }
    </script>
</body>
</html>
