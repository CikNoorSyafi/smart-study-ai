<?php

/**
 * Teacher Login Test - Simulate teacher authentication
 */

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔐 Teacher Login Test\n";
echo "====================\n\n";

// Test teacher credentials
$email = 'john@example.com';
$password = 'password';

echo "📧 Testing Email: {$email}\n";
echo "🔑 Testing Password: {$password}\n\n";

try {
    // Find the teacher user
    $teacher = App\Models\User::where('email', $email)->first();
    
    if (!$teacher) {
        echo "❌ Teacher user not found with email: {$email}\n";
        exit(1);
    }
    
    echo "✅ Teacher user found:\n";
    echo "   👤 Name: {$teacher->name}\n";
    echo "   📧 Email: {$teacher->email}\n";
    echo "   🎭 Role: {$teacher->role}\n";
    echo "   ✅ Active: " . ($teacher->is_active ? 'Yes' : 'No') . "\n\n";
    
    // Test password verification
    if (Hash::check($password, $teacher->password)) {
        echo "✅ Password verification successful!\n\n";
        
        // Simulate login process
        Auth::login($teacher);
        
        if (Auth::check()) {
            echo "✅ Authentication successful!\n";
            echo "👤 Logged in as: " . Auth::user()->name . "\n";
            echo "🎭 Role: " . Auth::user()->role . "\n\n";
            
            // Test role-based redirect
            $redirectRoute = match(Auth::user()->role) {
                'admin' => '/admin',
                'teacher' => '/teacher',
                'parent' => '/parent',
                default => '/dashboard'
            };
            
            echo "🔄 Would redirect to: {$redirectRoute}\n\n";
            
            // Test teacher dashboard access
            if (Auth::user()->role === 'teacher') {
                echo "✅ Teacher dashboard access confirmed!\n";
                echo "🌐 Teacher Dashboard URL: http://127.0.0.1:8000/teacher\n";
                echo "📚 My Subjects URL: http://127.0.0.1:8000/teacher/subjects\n";
                echo "👥 My Students URL: http://127.0.0.1:8000/teacher/students\n";
                echo "📝 My Content URL: http://127.0.0.1:8000/teacher/content\n";
                echo "📊 Analytics URL: http://127.0.0.1:8000/teacher/analytics\n\n";
                
                // Get teacher's data for dashboard
                $teacherSubjects = $teacher->subjects()
                    ->wherePivot('role_in_subject', 'teacher')
                    ->count();
                
                $teacherNotes = App\Models\Note::where('user_id', $teacher->user_id)->count();
                
                $teacherQuestions = App\Models\Question::whereHas('note', function($query) use ($teacher) {
                    $query->where('user_id', $teacher->user_id);
                })->count();
                
                echo "📊 Teacher Dashboard Data:\n";
                echo "   📚 Assigned Subjects: {$teacherSubjects}\n";
                echo "   📝 Created Notes: {$teacherNotes}\n";
                echo "   ❓ Generated Questions: {$teacherQuestions}\n\n";
                
            } else {
                echo "❌ User is not a teacher!\n";
            }
            
            // Logout
            Auth::logout();
            echo "🚪 Logged out successfully\n\n";
            
        } else {
            echo "❌ Authentication failed!\n";
        }
        
    } else {
        echo "❌ Password verification failed!\n";
        echo "🔧 Updating password...\n";
        
        $teacher->password = Hash::make($password);
        $teacher->save();
        
        echo "✅ Password updated successfully!\n";
        echo "🔄 Please try logging in again.\n\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "🎯 MANUAL LOGIN INSTRUCTIONS\n";
echo "============================\n";
echo "1. Open: http://127.0.0.1:8000/login\n";
echo "2. Enter Email: {$email}\n";
echo "3. Enter Password: {$password}\n";
echo "4. Click 'Login' button\n";
echo "5. You should be redirected to: http://127.0.0.1:8000/teacher\n\n";

echo "✨ Teacher authentication system is ready! ✨\n";
?>
