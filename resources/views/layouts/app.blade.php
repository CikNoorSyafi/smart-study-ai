<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Smart Study - Transform Learning into Questions')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /* Fallback styles */
                body { font-family: 'Inter', sans-serif; }
            </style>
        @endif

        @stack('styles')
    </head>
    <body class="bg-white font-sans">
        <!-- Header -->
        @unless(isset($hideHeader) && $hideHeader)
            <header class="bg-white shadow-sm border-b border-slate-200/60">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center py-4">
                        <!-- Logo -->
                        <div class="flex items-center">
                            <a href="{{ url('/') }}" class="flex items-center space-x-2 group">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-2xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent group-hover:from-blue-600 group-hover:to-indigo-600 transition-all duration-300">Smart Study</span>
                            </a>
                        </div>

                        <!-- Navigation -->
                        <nav class="hidden lg:flex items-center space-x-1">
                            <a href="{{ url('/') }}" class="px-4 py-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all duration-200 {{ request()->is('/') ? 'text-blue-600 bg-blue-50 font-medium' : '' }}">
                                Home
                            </a>
                            <a href="{{ url('/#features') }}" class="px-4 py-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all duration-200">
                                Features
                            </a>
                            <a href="{{ url('/#how-it-works') }}" class="px-4 py-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all duration-200">
                                How It Works
                            </a>
                            <a href="{{ url('/#pricing') }}" class="px-4 py-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all duration-200">
                                Pricing
                            </a>

                            <!-- Divider -->
                            <div class="w-px h-6 bg-slate-200 mx-2"></div>

                            <a href="#" class="px-4 py-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all duration-200">
                                Help
                            </a>
                        </nav>

                        <!-- Auth Section -->
                        <div class="flex items-center space-x-3">
                            @if (session('user'))
                                <!-- User Dropdown Menu -->
                                <div class="relative" id="user-menu">
                                    <!-- User Dropdown Button -->
                                    <button type="button" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-slate-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                            <span class="text-white text-sm font-medium">{{ substr(session('user.name'), 0, 1) }}</span>
                                        </div>
                                        <div class="hidden sm:block text-left">
                                            <p class="text-sm text-slate-700 font-medium">{{ session('user.name') }}</p>
                                            <p class="text-xs text-slate-500">{{ session('user.email') }}</p>
                                        </div>
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg border border-slate-200/60 py-2 z-50 hidden" id="user-dropdown">
                                        <!-- User Info Header -->
                                        <div class="px-4 py-3 border-b border-slate-100">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                                    <span class="text-white font-medium">{{ substr(session('user.name'), 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-slate-900">{{ session('user.name') }}</p>
                                                    <p class="text-xs text-slate-500">{{ session('user.email') }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Menu Items -->
                                        <div class="py-2">
                                            <a href="{{ url('/dashboard') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : '' }}">
                                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                                                </svg>
                                                Dashboard
                                            </a>
                                            <a href="{{ route('profile.settings') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200 {{ request()->routeIs('profile.settings') ? 'bg-blue-50 text-blue-600' : '' }}">
                                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                Profile Settings
                                            </a>
                                            @if(session('user.role') === 'student')
                                            <a href="{{ route('ai-chat.index') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200 {{ request()->routeIs('ai-chat.*') ? 'bg-blue-50 text-blue-600' : '' }}">
                                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                AI Study Assistant
                                            </a>
                                            <a href="#" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                                </svg>
                                                Practice Quizzes
                                            </a>
                                            <a href="#" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.168 18.477 18.582 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                </svg>
                                                My Notes
                                            </a>
                                            @else
                                            <a href="{{ route('questions.index') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200 {{ request()->routeIs('questions.*') ? 'bg-blue-50 text-blue-600' : '' }}">
                                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                </svg>
                                                My Questions
                                            </a>
                                            @endif
                                            @if(session('user.role') === 'admin' || session('user.role') === 'teacher')
                                            <a href="{{ route('pdf-upload.index') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200 {{ request()->routeIs('pdf-upload.*') ? 'bg-blue-50 text-blue-600' : '' }}">
                                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                                PDF Upload
                                            </a>
                                            @endif
                                            @if(session('user.role') === 'admin')
                                            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200 {{ request()->routeIs('admin.*') ? 'bg-blue-50 text-blue-600' : '' }}">
                                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                                </svg>
                                                Admin Panel
                                            </a>
                                            @endif
                                            <a href="#" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                Settings
                                            </a>
                                        </div>

                                        <!-- Divider -->
                                        <div class="border-t border-slate-100 my-2"></div>

                                        <!-- Logout -->
                                        <form method="POST" action="{{ route('logout') }}" class="block">
                                            @csrf
                                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                </svg>
                                                Sign Out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <!-- Guest Menu -->
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('login') }}" class="px-4 py-2 text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all duration-200 {{ request()->routeIs('login') ? 'text-blue-600 bg-blue-50 font-medium' : '' }}">
                                        Log In
                                    </a>
                                    <a href="{{ route('register') }}" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-sm hover:shadow-md font-medium {{ request()->routeIs('register') ? 'from-blue-700 to-indigo-700 shadow-md' : '' }}">
                                        Sign Up Free
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Mobile menu button -->
                        <div class="lg:hidden">
                            <button type="button" class="p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all duration-200" id="mobile-menu-button">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Mobile menu -->
                    <div class="lg:hidden hidden" id="mobile-menu">
                        <div class="px-2 pt-2 pb-4 space-y-1 border-t border-slate-200/60 bg-slate-50/50">
                            <!-- Navigation Links -->
                            <div class="space-y-1 mb-4">
                                <a href="{{ url('/') }}" class="block px-4 py-3 text-slate-600 hover:text-slate-900 hover:bg-white rounded-lg transition-all duration-200 {{ request()->is('/') ? 'text-blue-600 bg-blue-50 font-medium' : '' }}">
                                    Home
                                </a>
                                <a href="{{ url('/#features') }}" class="block px-4 py-3 text-slate-600 hover:text-slate-900 hover:bg-white rounded-lg transition-all duration-200">
                                    Features
                                </a>
                                <a href="{{ url('/#how-it-works') }}" class="block px-4 py-3 text-slate-600 hover:text-slate-900 hover:bg-white rounded-lg transition-all duration-200">
                                    How It Works
                                </a>
                                <a href="{{ url('/#pricing') }}" class="block px-4 py-3 text-slate-600 hover:text-slate-900 hover:bg-white rounded-lg transition-all duration-200">
                                    Pricing
                                </a>
                                <a href="#" class="block px-4 py-3 text-slate-600 hover:text-slate-900 hover:bg-white rounded-lg transition-all duration-200">
                                    Help
                                </a>
                            </div>

                            <!-- Auth Section for Mobile -->
                            @if (session('user'))
                                <div class="border-t border-slate-200 pt-4 space-y-1">
                                    <div class="px-4 py-2">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                                <span class="text-white font-medium">{{ substr(session('user.name'), 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <p class="text-slate-700 font-medium">{{ session('user.name') }}</p>
                                                <p class="text-slate-500 text-sm">{{ session('user.email') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ url('/dashboard') }}" class="flex items-center px-4 py-3 text-slate-600 hover:text-slate-900 hover:bg-white rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'text-blue-600 bg-blue-50 font-medium' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                                        </svg>
                                        Dashboard
                                    </a>
                                    <a href="{{ route('profile.settings') }}" class="flex items-center px-4 py-3 text-slate-600 hover:text-slate-900 hover:bg-white rounded-lg transition-all duration-200 {{ request()->routeIs('profile.settings') ? 'text-blue-600 bg-blue-50 font-medium' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Profile Settings
                                    </a>
                                    @if(session('user.role') === 'student')
                                    <a href="{{ route('ai-chat.index') }}" class="flex items-center px-4 py-3 text-slate-600 hover:text-slate-900 hover:bg-white rounded-lg transition-all duration-200 {{ request()->routeIs('ai-chat.*') ? 'text-blue-600 bg-blue-50 font-medium' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        AI Study Assistant
                                    </a>
                                    <a href="#" class="flex items-center px-4 py-3 text-slate-600 hover:text-slate-900 hover:bg-white rounded-lg transition-all duration-200">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                        Practice Quizzes
                                    </a>
                                    <a href="#" class="flex items-center px-4 py-3 text-slate-600 hover:text-slate-900 hover:bg-white rounded-lg transition-all duration-200">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.168 18.477 18.582 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                        My Notes
                                    </a>
                                    @else
                                    <a href="{{ route('questions.index') }}" class="flex items-center px-4 py-3 text-slate-600 hover:text-slate-900 hover:bg-white rounded-lg transition-all duration-200 {{ request()->routeIs('questions.*') ? 'text-blue-600 bg-blue-50 font-medium' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                        My Questions
                                    </a>
                                    @endif
                                    @if(session('user.role') === 'admin' || session('user.role') === 'teacher')
                                    <a href="{{ route('pdf-upload.index') }}" class="flex items-center px-4 py-3 text-slate-600 hover:text-slate-900 hover:bg-white rounded-lg transition-all duration-200 {{ request()->routeIs('pdf-upload.*') ? 'text-blue-600 bg-blue-50 font-medium' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        PDF Upload
                                    </a>
                                    @endif
                                    @if(session('user.role') === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-slate-600 hover:text-slate-900 hover:bg-white rounded-lg transition-all duration-200 {{ request()->routeIs('admin.*') ? 'text-blue-600 bg-blue-50 font-medium' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Admin Panel
                                    </a>
                                    @endif
                                    <a href="#" class="flex items-center px-4 py-3 text-slate-600 hover:text-slate-900 hover:bg-white rounded-lg transition-all duration-200">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Settings
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full text-left px-4 py-3 text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Sign Out
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="border-t border-slate-200 pt-4 space-y-2">
                                    <a href="{{ route('login') }}" class="block px-4 py-3 text-slate-600 hover:text-slate-900 hover:bg-white rounded-lg transition-all duration-200 {{ request()->routeIs('login') ? 'text-blue-600 bg-blue-50 font-medium' : '' }}">
                                        Log In
                                    </a>
                                    <a href="{{ route('register') }}" class="block px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 font-medium text-center {{ request()->routeIs('register') ? 'from-blue-700 to-indigo-700' : '' }}">
                                        Sign Up Free
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </header>
        @endunless

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-400 text-emerald-800 px-4 py-3 shadow-sm" role="alert">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 text-red-800 px-4 py-3 shadow-sm" role="alert">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        @unless(isset($hideFooter) && $hideFooter)
            <footer class="bg-gray-900 text-white py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid md:grid-cols-4 gap-8">
                        <!-- Company Info -->
                        <div>
                            <h3 class="text-2xl font-bold mb-4">Smart Study</h3>
                            <p class="text-gray-400 mb-4">
                                Transform your learning materials into engaging questions with the power of AI.
                            </p>
                            <div class="flex space-x-4">
                                <a href="#" class="text-gray-400 hover:text-white">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-white">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-white">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Product -->
                        <div>
                            <h4 class="text-lg font-semibold mb-4">Product</h4>
                            <ul class="space-y-2">
                                <li><a href="{{ url('/#features') }}" class="text-gray-400 hover:text-white">Features</a></li>
                                <li><a href="{{ url('/#pricing') }}" class="text-gray-400 hover:text-white">Pricing</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white">API</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white">Integrations</a></li>
                            </ul>
                        </div>

                        <!-- Support -->
                        <div>
                            <h4 class="text-lg font-semibold mb-4">Support</h4>
                            <ul class="space-y-2">
                                <li><a href="#" class="text-gray-400 hover:text-white">Help Center</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white">Documentation</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white">Contact Us</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white">Status</a></li>
                            </ul>
                        </div>

                        <!-- Company -->
                        <div>
                            <h4 class="text-lg font-semibold mb-4">Company</h4>
                            <ul class="space-y-2">
                                <li><a href="#" class="text-gray-400 hover:text-white">About</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white">Blog</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white">Careers</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white">Privacy</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white">Terms</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="border-t border-gray-800 mt-12 pt-8 text-center">
                        <p class="text-gray-400">
                            © {{ date('Y') }} Smart Study. All rights reserved.
                        </p>
                    </div>
                </div>
            </footer>
        @endunless

        @stack('scripts')

        <!-- Enhanced JavaScript -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Mobile menu toggle with smooth animation
                const mobileMenuButton = document.getElementById('mobile-menu-button');
                const mobileMenu = document.getElementById('mobile-menu');

                if (mobileMenuButton && mobileMenu) {
                    // Initialize mobile menu styles
                    mobileMenu.style.transition = 'opacity 200ms ease-in-out, transform 200ms ease-in-out';

                    mobileMenuButton.addEventListener('click', function() {
                        if (mobileMenu.classList.contains('hidden')) {
                            mobileMenu.classList.remove('hidden');
                            // Trigger animation
                            setTimeout(() => {
                                mobileMenu.style.opacity = '1';
                                mobileMenu.style.transform = 'translateY(0)';
                            }, 10);
                        } else {
                            mobileMenu.style.opacity = '0';
                            mobileMenu.style.transform = 'translateY(-10px)';
                            setTimeout(() => {
                                mobileMenu.classList.add('hidden');
                            }, 200);
                        }
                    });

                    // Close mobile menu when clicking outside
                    document.addEventListener('click', function(event) {
                        if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                            if (!mobileMenu.classList.contains('hidden')) {
                                mobileMenu.style.opacity = '0';
                                mobileMenu.style.transform = 'translateY(-10px)';
                                setTimeout(() => {
                                    mobileMenu.classList.add('hidden');
                                }, 200);
                            }
                        }
                    });
                }

                // User dropdown menu functionality
                const userMenuButton = document.getElementById('user-menu-button');
                const userDropdown = document.getElementById('user-dropdown');

                if (userMenuButton && userDropdown) {
                    // Initialize dropdown styles
                    userDropdown.style.transition = 'opacity 200ms ease-in-out, transform 200ms ease-in-out';
                    userDropdown.style.transform = 'translateY(-10px)';
                    userDropdown.style.opacity = '0';

                    userMenuButton.addEventListener('click', function(event) {
                        event.stopPropagation();

                        if (userDropdown.classList.contains('hidden')) {
                            // Close mobile menu if open
                            if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                                mobileMenu.style.opacity = '0';
                                mobileMenu.style.transform = 'translateY(-10px)';
                                setTimeout(() => {
                                    mobileMenu.classList.add('hidden');
                                }, 200);
                            }

                            // Open user dropdown
                            userDropdown.classList.remove('hidden');
                            userMenuButton.setAttribute('aria-expanded', 'true');
                            setTimeout(() => {
                                userDropdown.style.opacity = '1';
                                userDropdown.style.transform = 'translateY(0)';
                            }, 10);
                        } else {
                            // Close user dropdown
                            userDropdown.style.opacity = '0';
                            userDropdown.style.transform = 'translateY(-10px)';
                            userMenuButton.setAttribute('aria-expanded', 'false');
                            setTimeout(() => {
                                userDropdown.classList.add('hidden');
                            }, 200);
                        }
                    });

                    // Close user dropdown when clicking outside
                    document.addEventListener('click', function(event) {
                        if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                            if (!userDropdown.classList.contains('hidden')) {
                                userDropdown.style.opacity = '0';
                                userDropdown.style.transform = 'translateY(-10px)';
                                userMenuButton.setAttribute('aria-expanded', 'false');
                                setTimeout(() => {
                                    userDropdown.classList.add('hidden');
                                }, 200);
                            }
                        }
                    });

                    // Close dropdown on escape key
                    document.addEventListener('keydown', function(event) {
                        if (event.key === 'Escape' && !userDropdown.classList.contains('hidden')) {
                            userDropdown.style.opacity = '0';
                            userDropdown.style.transform = 'translateY(-10px)';
                            userMenuButton.setAttribute('aria-expanded', 'false');
                            setTimeout(() => {
                                userDropdown.classList.add('hidden');
                            }, 200);
                        }
                    });
                }

                // Auto-hide success/error messages after 5 seconds
                const alerts = document.querySelectorAll('[role="alert"]');
                alerts.forEach(alert => {
                    setTimeout(() => {
                        alert.style.transition = 'opacity 300ms ease-in-out, transform 300ms ease-in-out';
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            alert.remove();
                        }, 300);
                    }, 5000);
                });

                // Smooth scrolling for anchor links
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function (e) {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    });
                });
            });
        </script>
    </body>
</html>
