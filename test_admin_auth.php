<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Admin Authentication Test ===\n\n";

// Test 1: Check if admin user exists
echo "1. Checking admin user...\n";
$adminUser = App\Models\User::where('email', 'admin@smartstudy.com')->first();

if ($adminUser) {
    echo "✅ Admin user found!\n";
    echo "   Name: {$adminUser->name}\n";
    echo "   Email: {$adminUser->email}\n";
    echo "   Role: {$adminUser->role}\n";
    echo "   Active: " . ($adminUser->is_active ? 'Yes' : 'No') . "\n";
    echo "   User ID: {$adminUser->user_id}\n";
    echo "   Primary Key (id): " . ($adminUser->id ?? 'NULL') . "\n";
    echo "   Password Hash: " . substr($adminUser->password, 0, 20) . "...\n\n";
    
    // Test 2: Check password verification
    echo "2. Testing password verification...\n";
    $passwords = ['password123', 'password', 'admin123'];
    
    foreach ($passwords as $testPassword) {
        $isValid = Hash::check($testPassword, $adminUser->password);
        echo "   Password '{$testPassword}': " . ($isValid ? '✅ VALID' : '❌ Invalid') . "\n";
    }
    
    echo "\n";
    
    // Test 3: Test Laravel Auth attempt
    echo "3. Testing Laravel Auth::attempt...\n";
    $credentials = [
        'email' => 'admin@smartstudy.com',
        'password' => 'password123'
    ];
    
    try {
        $authResult = Auth::attempt($credentials);
        echo "   Auth::attempt result: " . ($authResult ? '✅ SUCCESS' : '❌ FAILED') . "\n";
        
        if ($authResult) {
            $authUser = Auth::user();
            echo "   Authenticated user: {$authUser->name} ({$authUser->email})\n";
            Auth::logout(); // Clean up
        }
    } catch (Exception $e) {
        echo "   Auth::attempt error: {$e->getMessage()}\n";
    }
    
} else {
    echo "❌ Admin user not found!\n";
    echo "   Total users in database: " . App\Models\User::count() . "\n";
    
    // List all users
    echo "\n   All users:\n";
    $users = App\Models\User::select('name', 'email', 'role')->get();
    foreach ($users as $user) {
        echo "   - {$user->name} ({$user->email}) - {$user->role}\n";
    }
}

echo "\n=== Test Complete ===\n";
