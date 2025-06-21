<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Access Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-center mb-6">Admin Access Test</h1>
            
            @if(auth()->check())
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <strong>✅ Logged In!</strong><br>
                    User: {{ auth()->user()->name }}<br>
                    Email: {{ auth()->user()->email }}<br>
                    Role: {{ auth()->user()->role ?? 'No role' }}<br>
                    Active: {{ auth()->user()->is_active ? 'Yes' : 'No' }}
                </div>
                
                @if(auth()->user()->role === 'admin')
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
                        <strong>🎉 Admin Access Granted!</strong><br>
                        You should be able to access the admin panel.
                    </div>
                    
                    <div class="space-y-2">
                        <a href="{{ route('admin.dashboard') }}" class="block w-full bg-blue-600 text-white text-center py-2 px-4 rounded hover:bg-blue-700">
                            Go to Admin Dashboard (Unified CRUD)
                        </a>
                        <a href="{{ route('admin.users-crud.index') }}" class="block w-full bg-green-600 text-white text-center py-2 px-4 rounded hover:bg-green-700">
                            Go to User Management
                        </a>
                        <a href="{{ route('admin.subjects.index') }}" class="block w-full bg-purple-600 text-white text-center py-2 px-4 rounded hover:bg-purple-700">
                            Go to Subject Management
                        </a>
                    </div>
                @else
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <strong>❌ Access Denied!</strong><br>
                        You need admin role to access the admin panel.<br>
                        Current role: {{ auth()->user()->role ?? 'No role' }}
                    </div>
                @endif
                
                <form action="{{ route('logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-700">
                        Logout
                    </button>
                </form>
            @else
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                    <strong>⚠️ Not Logged In!</strong><br>
                    Please login to test admin access.
                </div>
                
                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded">
                        <h3 class="font-semibold mb-2">Admin Credentials:</h3>
                        <p><strong>Email:</strong> admin@smartstudy.com</p>
                        <p><strong>Password:</strong> password</p>
                    </div>
                    
                    <a href="{{ route('login') }}" class="block w-full bg-blue-600 text-white text-center py-2 px-4 rounded hover:bg-blue-700">
                        Go to Login Page
                    </a>
                </div>
            @endif
            
            <div class="mt-6 pt-4 border-t border-gray-200">
                <h3 class="font-semibold mb-2">Quick Links:</h3>
                <div class="space-y-1 text-sm">
                    <a href="{{ route('login') }}" class="block text-blue-600 hover:underline">Login Page</a>
                    <a href="{{ route('dashboard') }}" class="block text-blue-600 hover:underline">User Dashboard</a>
                    <a href="/admin/dashboard" class="block text-blue-600 hover:underline">Admin Dashboard (Direct)</a>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-200">
                <h3 class="font-semibold mb-2">Debug Tools:</h3>
                <div class="space-y-2">
                    <a href="{{ route('quick.login') }}" class="block w-full bg-green-600 text-white text-center py-2 px-4 rounded hover:bg-green-700">
                        🚀 Quick Login as Admin
                    </a>
                    <a href="{{ route('debug.auth') }}" class="block w-full bg-yellow-600 text-white text-center py-2 px-4 rounded hover:bg-yellow-700" target="_blank">
                        🔍 Debug Auth Status (JSON)
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
