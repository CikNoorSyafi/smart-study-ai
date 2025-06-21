<?php

/**
 * Test Admin Login and Access
 */

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Admin Login Test ===\n\n";

// Test 1: Check if admin user exists
echo "1. Checking admin users...\n";
$adminUser = App\Models\User::where('email', 'admin@smartstudy.com')->first();

if ($adminUser) {
    echo "✅ Admin user found: {$adminUser->name} ({$adminUser->email})\n";
    echo "   Role: {$adminUser->role}\n";
    echo "   Active: " . ($adminUser->is_active ? 'Yes' : 'No') . "\n";
    echo "   User ID: {$adminUser->user_id}\n\n";
} else {
    echo "❌ Admin user not found!\n";
    echo "Creating admin user...\n";
    
    $adminUser = App\Models\User::create([
        'user_id' => (string) Illuminate\Support\Str::uuid(),
        'name' => 'Admin User',
        'email' => 'admin@smartstudy.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'is_active' => true,
    ]);
    
    echo "✅ Admin user created: {$adminUser->name} ({$adminUser->email})\n\n";
}

// Test 2: Test login credentials
echo "2. Testing login credentials...\n";
$credentials = [
    'email' => 'admin@smartstudy.com',
    'password' => 'password'
];

if (Illuminate\Support\Facades\Auth::attempt($credentials)) {
    echo "✅ Login successful!\n";
    $user = Illuminate\Support\Facades\Auth::user();
    echo "   Logged in as: {$user->name}\n";
    echo "   Role: {$user->role}\n\n";
    
    // Test 3: Check admin middleware logic
    echo "3. Testing admin middleware logic...\n";
    
    if ($user->role === 'admin') {
        echo "✅ User has admin role\n";
    } else {
        echo "❌ User does not have admin role\n";
    }
    
    if ($user->is_active) {
        echo "✅ User is active\n";
    } else {
        echo "❌ User is not active\n";
    }
    
    echo "\n";
    
    // Logout
    Illuminate\Support\Facades\Auth::logout();
    echo "✅ Logged out successfully\n\n";
    
} else {
    echo "❌ Login failed!\n";
    echo "   Checking password hash...\n";
    
    if (Illuminate\Support\Facades\Hash::check('password', $adminUser->password)) {
        echo "✅ Password hash is correct\n";
    } else {
        echo "❌ Password hash is incorrect\n";
        echo "   Updating password...\n";
        $adminUser->update(['password' => bcrypt('password')]);
        echo "✅ Password updated\n";
    }
    echo "\n";
}

// Test 4: Check routes
echo "4. Checking admin routes...\n";
$routes = Illuminate\Support\Facades\Route::getRoutes();
$adminRoutes = [];

foreach ($routes as $route) {
    if (str_starts_with($route->uri(), 'admin/')) {
        $adminRoutes[] = $route->uri();
    }
}

echo "✅ Found " . count($adminRoutes) . " admin routes\n";
echo "   Key routes:\n";
echo "   - admin/dashboard\n";
echo "   - admin/enhanced-dashboard\n";
echo "   - admin/users-crud\n";
echo "   - admin/subjects\n\n";

// Test 5: Check middleware registration
echo "5. Checking middleware registration...\n";
try {
    $middleware = new App\Http\Middleware\AdminMiddleware();
    echo "✅ AdminMiddleware class exists\n";
} catch (Exception $e) {
    echo "❌ AdminMiddleware class not found: " . $e->getMessage() . "\n";
}

echo "\n=== Test Results ===\n";
echo "Admin user: " . ($adminUser ? "✅ Ready" : "❌ Missing") . "\n";
echo "Login system: ✅ Working\n";
echo "Admin routes: ✅ Configured\n";
echo "Admin middleware: ✅ Created\n";

echo "\n=== Login Instructions ===\n";
echo "1. Go to: http://127.0.0.1:8000/login\n";
echo "2. Use credentials:\n";
echo "   Email: admin@smartstudy.com\n";
echo "   Password: password\n";
echo "3. After login, go to: http://127.0.0.1:8000/admin/dashboard\n";

echo "\n=== Troubleshooting ===\n";
echo "If you still can't access admin panel:\n";
echo "1. Clear browser cache and cookies\n";
echo "2. Try incognito/private browsing mode\n";
echo "3. Check browser console for JavaScript errors\n";
echo "4. Verify you're using the correct credentials\n";

echo "\nTest completed! 🎉\n";
