<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Fixing Admin Password ===\n\n";

// Find admin user
$admin = App\Models\User::where('email', 'admin@smartstudy.com')->first();

if ($admin) {
    echo "Found admin user: {$admin->name}\n";
    
    // Update password to password123
    $admin->password = Hash::make('password123');
    $admin->save();
    
    echo "✅ Password updated to 'password123'\n\n";
    
    // Test the new password
    echo "Testing new password...\n";
    $isValid = Hash::check('password123', $admin->password);
    echo "Password 'password123': " . ($isValid ? '✅ VALID' : '❌ Invalid') . "\n";
    
    // Test Auth::attempt with new password
    echo "\nTesting Auth::attempt...\n";
    $credentials = [
        'email' => 'admin@smartstudy.com',
        'password' => 'password123'
    ];
    
    try {
        $authResult = Auth::attempt($credentials);
        echo "Auth::attempt result: " . ($authResult ? '✅ SUCCESS' : '❌ FAILED') . "\n";
        
        if ($authResult) {
            $authUser = Auth::user();
            echo "Authenticated user: {$authUser->name} ({$authUser->email})\n";
            Auth::logout();
        } else {
            echo "Auth failed - checking User model configuration...\n";
            
            // Check if the issue is with the primary key
            echo "User model primary key: " . (new App\Models\User())->getKeyName() . "\n";
            echo "User model key type: " . (new App\Models\User())->getKeyType() . "\n";
            echo "User model incrementing: " . ((new App\Models\User())->getIncrementing() ? 'true' : 'false') . "\n";
        }
    } catch (Exception $e) {
        echo "Auth error: {$e->getMessage()}\n";
    }
    
} else {
    echo "❌ Admin user not found!\n";
}

echo "\n=== Fix Complete ===\n";
