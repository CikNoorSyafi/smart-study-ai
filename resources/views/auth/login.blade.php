@extends('layouts.auth')

@section('title', 'Login - Smart Study')

@push('styles')
<style>
    .auth-container {
        min-height: calc(100vh - 80px);
    }
</style>
@endpush

@section('content')
    <div class="auth-container bg-slate-50 flex items-center justify-center py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-6 sm:space-y-8">
            <!-- Header -->
            <div class="text-center">
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-2">Welcome back</h2>
                <p class="text-slate-600">Sign in to your Smart Study account</p>
            </div>

            <!-- Demo Credentials -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200/60 rounded-xl p-4 shadow-sm">
                <div class="flex items-center mb-3">
                    <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-blue-800">Demo Credentials</h3>
                </div>
                <div class="text-sm text-blue-700 space-y-2">
                    <div class="flex items-center">
                        <span class="font-medium text-blue-800 w-16">Email:</span>
                        <code class="bg-white/60 px-2 py-1 rounded text-blue-900 font-mono text-xs">demo@smartstudy.com</code>
                    </div>
                    <div class="flex items-center">
                        <span class="font-medium text-blue-800 w-16">Password:</span>
                        <code class="bg-white/60 px-2 py-1 rounded text-blue-900 font-mono text-xs">demo123</code>
                    </div>
                </div>
            </div>

            <!-- Login Form -->
            <div class="bg-white py-8 px-6 shadow-lg rounded-xl border border-slate-200/60">
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-400 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                                <ul class="mt-2 text-sm text-red-700 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li class="flex items-center">
                                            <span class="w-1 h-1 bg-red-500 rounded-full mr-2"></span>
                                            {{ $error }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Google OAuth Button (Primary) -->
                <div class="mb-6">
                    <a
                        href="{{ route('auth.google') }}"
                        id="google-oauth-btn"
                        class="w-full inline-flex justify-center items-center py-3 px-4 border border-slate-300 rounded-lg shadow-sm bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-400 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        onclick="showGoogleLoading(this)"
                    >
                        <svg class="w-5 h-5 text-red-500" viewBox="0 0 24 24" id="google-icon">
                            <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <svg class="w-5 h-5 text-slate-400 animate-spin hidden" id="loading-icon" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="ml-3 font-medium" id="google-text">Continue with Google</span>
                    </a>
                </div>

                <!-- Divider -->
                <div class="relative mb-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-3 bg-white text-slate-500 font-medium">Or sign in with email</span>
                    </div>
                </div>

                <form id="login-form" class="space-y-6" action="{{ route('login') }}" method="POST">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                            Email address
                        </label>
                        <div class="relative">
                            <input
                                id="email"
                                name="email"
                                type="email"
                                autocomplete="email"
                                required
                                value="{{ old('email') }}"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 @error('email') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                                placeholder="Enter your email"
                            >
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="current-password"
                                required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 @error('password') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                                placeholder="Enter your password"
                            >
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input
                                id="remember"
                                name="remember"
                                type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500/20 border-slate-300 rounded transition-all duration-200"
                            >
                            <label for="remember" class="ml-2 block text-sm text-slate-700">
                                Remember me
                            </label>
                        </div>

                        <div class="text-sm">
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-700 transition-colors duration-200">
                                Forgot your password?
                            </a>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button
                            type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500/50 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Sign in
                        </button>
                    </div>


                </form>
            </div>

            <!-- Sign Up Link -->
            <div class="text-center">
                <p class="text-sm text-slate-600">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-700 transition-colors duration-200">
                        Sign up for free
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function showGoogleLoading(button) {
        // Prevent double clicks
        if (button.classList.contains('loading')) {
            return false;
        }

        // Add loading state
        button.classList.add('loading');
        button.style.pointerEvents = 'none';

        // Hide Google icon and show loading spinner
        const googleIcon = document.getElementById('google-icon');
        const loadingIcon = document.getElementById('loading-icon');
        const buttonText = document.getElementById('google-text');

        googleIcon.classList.add('hidden');
        loadingIcon.classList.remove('hidden');
        buttonText.textContent = 'Connecting to Google...';

        // Change button appearance
        button.classList.remove('hover:bg-slate-50', 'hover:border-slate-400', 'transform', 'hover:scale-[1.02]');
        button.classList.add('bg-slate-50', 'border-slate-400', 'cursor-not-allowed');

        // Allow the navigation to proceed
        return true;
    }

    // Handle form submission loading state
    document.getElementById('login-form').addEventListener('submit', function(e) {
        const submitButton = this.querySelector('button[type="submit"]');
        const buttonText = submitButton.querySelector('span');
        const buttonIcon = submitButton.querySelector('svg');

        // Prevent double submission
        if (submitButton.disabled) {
            e.preventDefault();
            return false;
        }

        // Add loading state
        submitButton.disabled = true;
        submitButton.classList.add('opacity-75', 'cursor-not-allowed');
        buttonText.textContent = 'Signing in...';

        // Add spinner to button
        buttonIcon.classList.add('animate-spin');
    });

    // Show success message if redirected back with success
    @if(session('success'))
        // Show success notification
        const successMessage = document.createElement('div');
        successMessage.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
        successMessage.textContent = '{{ session('success') }}';
        document.body.appendChild(successMessage);

        // Animate in
        setTimeout(() => {
            successMessage.classList.remove('translate-x-full');
        }, 100);

        // Animate out after 3 seconds
        setTimeout(() => {
            successMessage.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(successMessage);
            }, 300);
        }, 3000);
    @endif

    // Show error message if OAuth failed
    @if(session('error'))
        // Show error notification
        const errorMessage = document.createElement('div');
        errorMessage.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
        errorMessage.textContent = '{{ session('error') }}';
        document.body.appendChild(errorMessage);

        // Animate in
        setTimeout(() => {
            errorMessage.classList.remove('translate-x-full');
        }, 100);

        // Animate out after 5 seconds
        setTimeout(() => {
            errorMessage.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(errorMessage);
            }, 300);
        }, 5000);
    @endif
</script>
@endsection
