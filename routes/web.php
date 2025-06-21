<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleController;

Route::get('/', function () {
    return view('welcome');
});

// Admin Access Test Route
Route::get('/admin-test', function () {
    return view('admin-access-test');
})->name('admin.test');

// Debug Authentication Route
Route::get('/debug-auth', function () {
    $data = [
        'laravel_auth_check' => auth()->check(),
        'laravel_auth_user' => auth()->user(),
        'session_has_user' => session()->has('user'),
        'session_user' => session()->get('user'),
        'session_id' => session()->getId(),
        'all_session_data' => session()->all(),
    ];

    return response()->json($data, 200, [], JSON_PRETTY_PRINT);
})->name('debug.auth');

// Quick Login Test Route
Route::get('/quick-login', function () {
    $credentials = ['email' => 'admin@questioncraft.com', 'password' => 'password'];

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        session()->put('user', [
            'id' => $user->user_id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Logged in successfully as ' . $user->name);
    } else {
        return redirect('/admin-test')->with('error', 'Login failed');
    }
})->name('quick.login');

// Authentication Routes
Route::middleware('session.guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Google OAuth Routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Google OAuth Test Route
Route::get('/test-google-oauth', function () {
    $config = [
        'google_client_id' => env('GOOGLE_CLIENT_ID'),
        'google_client_secret' => env('GOOGLE_CLIENT_SECRET') ? 'Set (hidden)' : 'Not set',
        'google_redirect_uri' => env('GOOGLE_REDIRECT_URI'),
        'socialite_installed' => class_exists('Laravel\Socialite\Facades\Socialite'),
        'google_service_config' => config('services.google'),
    ];

    return response()->json([
        'status' => 'Google OAuth Configuration Check',
        'config' => $config,
        'routes' => [
            'auth_google' => route('auth.google'),
            'auth_callback' => route('auth.google.callback'),
        ],
        'ready' => !empty($config['google_client_id']) && !empty(env('GOOGLE_CLIENT_SECRET')),
    ], 200, [], JSON_PRETTY_PRINT);
})->name('test.google.oauth');

// Google Account Management (for authenticated users)
Route::middleware('session.auth')->group(function () {
    Route::get('/auth/google/link', [GoogleController::class, 'linkGoogleAccount'])->name('auth.google.link');
    Route::post('/auth/google/unlink', [GoogleController::class, 'unlinkGoogleAccount'])->name('auth.google.unlink');
});

Route::middleware('session.auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile Settings Routes
    Route::get('/profile', function () {
        return view('profile.settings');
    })->name('profile.settings');

    Route::post('/profile', function () {
        // Validate the request
        $validated = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|confirmed',
            'timezone' => 'required|string',
            'language' => 'required|string',
            'notifications_email' => 'boolean',
            'notifications_browser' => 'boolean',
            'notifications_marketing' => 'boolean',
        ]);

        // Get current user data
        $user = session('user');

        // Update user session data (in a real app, this would update the database)
        $user['name'] = $validated['name'];
        $user['email'] = $validated['email'];

        // Store updated user data in session
        session(['user' => $user]);

        return redirect()->route('profile.settings')->with('success', 'Profile updated successfully!');
    })->name('profile.update');

    // My Questions Routes
    Route::get('/questions', function () {
        // Sample questions data (in a real app, this would come from database)
        $questions = [
            [
                'id' => 1,
                'title' => 'Introduction to Machine Learning',
                'description' => 'Questions generated from ML fundamentals document',
                'question_count' => 15,
                'difficulty' => 'Intermediate',
                'category' => 'Technology',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(1),
                'status' => 'completed',
                'source_type' => 'PDF Document',
                'source_name' => 'ml_fundamentals.pdf',
                'tags' => ['machine-learning', 'ai', 'algorithms'],
                'questions' => [
                    ['question' => 'What is supervised learning?', 'type' => 'multiple_choice', 'difficulty' => 'easy'],
                    ['question' => 'Explain the difference between classification and regression.', 'type' => 'short_answer', 'difficulty' => 'medium'],
                    ['question' => 'What are the main types of machine learning algorithms?', 'type' => 'multiple_choice', 'difficulty' => 'easy'],
                ]
            ],
            [
                'id' => 2,
                'title' => 'JavaScript ES6 Features',
                'description' => 'Modern JavaScript concepts and syntax',
                'question_count' => 12,
                'difficulty' => 'Beginner',
                'category' => 'Programming',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(3),
                'status' => 'completed',
                'source_type' => 'Web Article',
                'source_name' => 'ES6 Tutorial - MDN',
                'tags' => ['javascript', 'es6', 'programming'],
                'questions' => [
                    ['question' => 'What is arrow function syntax?', 'type' => 'multiple_choice', 'difficulty' => 'easy'],
                    ['question' => 'How do template literals work?', 'type' => 'short_answer', 'difficulty' => 'medium'],
                ]
            ],
            [
                'id' => 3,
                'title' => 'Digital Marketing Strategy',
                'description' => 'Comprehensive guide to modern marketing',
                'question_count' => 8,
                'difficulty' => 'Advanced',
                'category' => 'Business',
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
                'status' => 'processing',
                'source_type' => 'Text Input',
                'source_name' => 'Marketing Strategy Notes',
                'tags' => ['marketing', 'strategy', 'business'],
                'questions' => []
            ],
            [
                'id' => 4,
                'title' => 'React Hooks Deep Dive',
                'description' => 'Advanced React hooks patterns and best practices',
                'question_count' => 20,
                'difficulty' => 'Advanced',
                'category' => 'Programming',
                'created_at' => now()->subHours(6),
                'updated_at' => now()->subHours(2),
                'status' => 'completed',
                'source_type' => 'Video Transcript',
                'source_name' => 'React Hooks Masterclass',
                'tags' => ['react', 'hooks', 'frontend'],
                'questions' => [
                    ['question' => 'When should you use useCallback?', 'type' => 'multiple_choice', 'difficulty' => 'hard'],
                    ['question' => 'Explain the useEffect cleanup function.', 'type' => 'essay', 'difficulty' => 'medium'],
                ]
            ],
            [
                'id' => 5,
                'title' => 'Data Structures Fundamentals',
                'description' => 'Core data structures and algorithms',
                'question_count' => 25,
                'difficulty' => 'Intermediate',
                'category' => 'Computer Science',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subHours(8),
                'status' => 'completed',
                'source_type' => 'PDF Document',
                'source_name' => 'data_structures_guide.pdf',
                'tags' => ['data-structures', 'algorithms', 'computer-science'],
                'questions' => [
                    ['question' => 'What is the time complexity of binary search?', 'type' => 'multiple_choice', 'difficulty' => 'medium'],
                    ['question' => 'Compare arrays vs linked lists.', 'type' => 'essay', 'difficulty' => 'medium'],
                ]
            ]
        ];

        return view('questions.index', compact('questions'));
    })->name('questions.index');

    Route::get('/questions/{id}', function ($id) {
        // Sample question detail (in a real app, this would come from database)
        $question = [
            'id' => $id,
            'title' => 'Introduction to Machine Learning',
            'description' => 'Questions generated from ML fundamentals document',
            'question_count' => 15,
            'difficulty' => 'Intermediate',
            'category' => 'Technology',
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(1),
            'status' => 'completed',
            'source_type' => 'PDF Document',
            'source_name' => 'ml_fundamentals.pdf',
            'tags' => ['machine-learning', 'ai', 'algorithms'],
            'questions' => [
                [
                    'id' => 1,
                    'question' => 'What is supervised learning?',
                    'type' => 'multiple_choice',
                    'difficulty' => 'easy',
                    'options' => [
                        'A type of learning where the algorithm learns from labeled data',
                        'A type of learning where the algorithm learns without any data',
                        'A type of learning where the algorithm supervises other algorithms',
                        'A type of learning that requires human supervision at all times'
                    ],
                    'correct_answer' => 0,
                    'explanation' => 'Supervised learning uses labeled training data to learn a mapping from inputs to outputs.'
                ],
                [
                    'id' => 2,
                    'question' => 'Explain the difference between classification and regression.',
                    'type' => 'short_answer',
                    'difficulty' => 'medium',
                    'sample_answer' => 'Classification predicts discrete categories or classes, while regression predicts continuous numerical values.'
                ],
                [
                    'id' => 3,
                    'question' => 'What are the main types of machine learning algorithms?',
                    'type' => 'multiple_choice',
                    'difficulty' => 'easy',
                    'options' => [
                        'Supervised, Unsupervised, and Reinforcement Learning',
                        'Linear and Non-linear algorithms',
                        'Simple and Complex algorithms',
                        'Fast and Slow algorithms'
                    ],
                    'correct_answer' => 0,
                    'explanation' => 'The three main paradigms of machine learning are supervised, unsupervised, and reinforcement learning.'
                ]
            ]
        ];

        return view('questions.show', compact('question'));
    })->name('questions.show');
});

// Teacher Routes
Route::middleware(['session.auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/', [App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/subjects', [App\Http\Controllers\Teacher\DashboardController::class, 'subjects'])->name('subjects');
    Route::get('/students', [App\Http\Controllers\Teacher\DashboardController::class, 'students'])->name('students');
    Route::get('/content', [App\Http\Controllers\Teacher\DashboardController::class, 'content'])->name('content');
    Route::get('/analytics', [App\Http\Controllers\Teacher\DashboardController::class, 'analytics'])->name('analytics');

    // Phase 2: Hub pages
    Route::get('/create', [App\Http\Controllers\Teacher\DashboardController::class, 'createHub'])->name('create.hub');
    Route::get('/students/manage', [App\Http\Controllers\Teacher\DashboardController::class, 'studentsHub'])->name('students.hub');

    // Notes CRUD routes
    Route::get('/notes/create', [App\Http\Controllers\Teacher\DashboardController::class, 'createNote'])->name('notes.create');
    Route::post('/notes', [App\Http\Controllers\Teacher\DashboardController::class, 'storeNote'])->name('notes.store');
    Route::get('/notes/{note}', [App\Http\Controllers\Teacher\DashboardController::class, 'showNote'])->name('notes.show');
    Route::get('/notes/{note}/edit', [App\Http\Controllers\Teacher\DashboardController::class, 'editNote'])->name('notes.edit');
    Route::put('/notes/{note}', [App\Http\Controllers\Teacher\DashboardController::class, 'updateNote'])->name('notes.update');
    Route::delete('/notes/{note}', [App\Http\Controllers\Teacher\DashboardController::class, 'deleteNote'])->name('notes.delete');

    // Questions CRUD routes
    Route::get('/questions/create', [App\Http\Controllers\Teacher\DashboardController::class, 'createQuestion'])->name('questions.create');
    Route::post('/questions', [App\Http\Controllers\Teacher\DashboardController::class, 'storeQuestion'])->name('questions.store');
    Route::get('/questions/{question}/edit', [App\Http\Controllers\Teacher\DashboardController::class, 'editQuestion'])->name('questions.edit');
    Route::put('/questions/{question}', [App\Http\Controllers\Teacher\DashboardController::class, 'updateQuestion'])->name('questions.update');
    Route::delete('/questions/{question}', [App\Http\Controllers\Teacher\DashboardController::class, 'deleteQuestion'])->name('questions.delete');

    // Student management routes
    Route::post('/students/{student}/assign', [App\Http\Controllers\Teacher\DashboardController::class, 'assignStudent'])->name('students.assign');
    Route::delete('/students/{student}/remove', [App\Http\Controllers\Teacher\DashboardController::class, 'removeStudent'])->name('students.remove');

    // Bulk operations
    Route::post('/notes/bulk-delete', [App\Http\Controllers\Teacher\DashboardController::class, 'bulkDeleteNotes'])->name('notes.bulk-delete');
    Route::post('/questions/bulk-delete', [App\Http\Controllers\Teacher\DashboardController::class, 'bulkDeleteQuestions'])->name('questions.bulk-delete');

    // Notification routes
    Route::get('/notifications', [App\Http\Controllers\Teacher\DashboardController::class, 'getNotifications'])->name('notifications.get');
    Route::post('/notifications/{notification}/read', [App\Http\Controllers\Teacher\DashboardController::class, 'markNotificationRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\Teacher\DashboardController::class, 'markAllNotificationsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [App\Http\Controllers\Teacher\DashboardController::class, 'deleteNotification'])->name('notifications.delete');
});

// Admin Routes
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/', function () {
        // Enhanced admin dashboard data (in a real app, this would come from database)
        $stats = [
            'total_users' => 1247,
            'active_users' => 892,
            'users_growth' => 12.5,
            'total_questions' => 15634,
            'questions_today' => 156,
            'questions_week' => 1834,
            'total_documents' => 3421,
            'documents_processing' => 23,
            'processing_rate' => 94.2,
            'monthly_revenue' => 24750,
            'revenue_growth' => 18.3,
            'revenue_target' => 30000,
            'new_users_today' => 23,
            'server_uptime' => '99.9%',
            'storage_used' => '67%'
        ];

        $recentUsers = [
            ['id' => 1, 'name' => 'John Smith', 'email' => 'john@example.com', 'created_at' => now()->subHours(2), 'status' => 'active'],
            ['id' => 2, 'name' => 'Sarah Johnson', 'email' => 'sarah@example.com', 'created_at' => now()->subHours(5), 'status' => 'active'],
            ['id' => 3, 'name' => 'Mike Wilson', 'email' => 'mike@example.com', 'created_at' => now()->subHours(8), 'status' => 'pending'],
            ['id' => 4, 'name' => 'Emily Davis', 'email' => 'emily@example.com', 'created_at' => now()->subDay(), 'status' => 'active'],
            ['id' => 5, 'name' => 'David Brown', 'email' => 'david@example.com', 'created_at' => now()->subDays(2), 'status' => 'inactive'],
        ];

        $recentActivity = [
            ['user' => 'John Smith', 'action' => 'Created question set', 'details' => 'Machine Learning Basics', 'time' => now()->subMinutes(15), 'type' => 'question'],
            ['user' => 'Sarah Johnson', 'action' => 'Uploaded document', 'details' => 'data_science.pdf', 'time' => now()->subMinutes(32), 'type' => 'document'],
            ['user' => 'Mike Wilson', 'action' => 'Registered account', 'details' => 'New user signup', 'time' => now()->subHours(1), 'type' => 'user'],
            ['user' => 'Emily Davis', 'action' => 'Generated questions', 'details' => '25 questions from React documentation', 'time' => now()->subHours(2), 'type' => 'question'],
            ['user' => 'David Brown', 'action' => 'Updated profile', 'details' => 'Changed notification settings', 'time' => now()->subHours(3), 'type' => 'user'],
            ['user' => 'Lisa Anderson', 'action' => 'Uploaded document', 'details' => 'react_tutorial.pdf', 'time' => now()->subHours(4), 'type' => 'document'],
        ];

        $systemHealth = [
            ['service' => 'Web Server', 'status' => 'healthy', 'uptime' => '99.9%', 'response_time' => '45ms'],
            ['service' => 'Database', 'status' => 'healthy', 'uptime' => '99.8%', 'response_time' => '12ms'],
            ['service' => 'File Storage', 'status' => 'healthy', 'uptime' => '99.9%', 'response_time' => '23ms'],
            ['service' => 'AI Service', 'status' => 'warning', 'uptime' => '98.5%', 'response_time' => '156ms'],
            ['service' => 'Email Service', 'status' => 'healthy', 'uptime' => '99.7%', 'response_time' => '89ms'],
            ['service' => 'Cache Server', 'status' => 'healthy', 'uptime' => '99.6%', 'response_time' => '8ms'],
        ];

        // Chart data for user growth and question generation
        $chartData = [
            'user_growth' => [
                'Mon' => 45,
                'Tue' => 52,
                'Wed' => 38,
                'Thu' => 61,
                'Fri' => 73,
                'Sat' => 29,
                'Sun' => 41,
            ],
            'questions_generated' => [
                'Mon' => 234,
                'Tue' => 287,
                'Wed' => 195,
                'Thu' => 342,
                'Fri' => 398,
                'Sat' => 156,
                'Sun' => 223,
            ],
        ];

        // Performance metrics
        $performance = [
            'cpu' => 34,
            'memory' => 67,
            'disk' => 45,
            'network' => 23,
            'database' => 28,
        ];

        // Quick stats
        $quickStats = [
            'online_users' => 127,
            'avg_session' => '8m 32s',
            'bounce_rate' => 23,
            'conversion_rate' => 4.7,
            'mrr' => 24.8,
            'support_tickets' => 3,
        ];

        return view('admin.index', compact('stats', 'recentUsers', 'recentActivity', 'systemHealth', 'chartData', 'performance', 'quickStats'));
    })->name('index');

    // Main Admin Dashboard with CRUD functionality (using controller)
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Users Management
    Route::get('/users', function () {
        $users = [
            ['id' => 1, 'name' => 'John Smith', 'email' => 'john@example.com', 'role' => 'user', 'status' => 'active', 'created_at' => now()->subDays(30), 'last_login' => now()->subHours(2), 'questions_count' => 45],
            ['id' => 2, 'name' => 'Sarah Johnson', 'email' => 'sarah@example.com', 'role' => 'user', 'status' => 'active', 'created_at' => now()->subDays(25), 'last_login' => now()->subHours(5), 'questions_count' => 32],
            ['id' => 3, 'name' => 'Mike Wilson', 'email' => 'mike@example.com', 'role' => 'user', 'status' => 'pending', 'created_at' => now()->subHours(8), 'last_login' => null, 'questions_count' => 0],
            ['id' => 4, 'name' => 'Emily Davis', 'email' => 'emily@example.com', 'role' => 'moderator', 'status' => 'active', 'created_at' => now()->subDays(60), 'last_login' => now()->subDay(), 'questions_count' => 128],
            ['id' => 5, 'name' => 'David Brown', 'email' => 'david@example.com', 'role' => 'user', 'status' => 'inactive', 'created_at' => now()->subDays(90), 'last_login' => now()->subWeek(), 'questions_count' => 12],
            ['id' => 6, 'name' => 'Lisa Anderson', 'email' => 'lisa@example.com', 'role' => 'user', 'status' => 'active', 'created_at' => now()->subDays(15), 'last_login' => now()->subHours(12), 'questions_count' => 67],
            ['id' => 7, 'name' => 'Tom Garcia', 'email' => 'tom@example.com', 'role' => 'user', 'status' => 'active', 'created_at' => now()->subDays(45), 'last_login' => now()->subHours(1), 'questions_count' => 89],
            ['id' => 8, 'name' => 'Anna Martinez', 'email' => 'anna@example.com', 'role' => 'user', 'status' => 'suspended', 'created_at' => now()->subDays(20), 'last_login' => now()->subDays(3), 'questions_count' => 23],
        ];

        return view('admin.users', compact('users'));
    })->name('users');

    // System Settings
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('settings');

    // Analytics
    Route::get('/analytics', function () {
        return view('admin.analytics');
    })->name('analytics');
});

// Enhanced Admin CRUD Routes (protected by admin middleware)
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    // Enhanced Dashboard functionality now integrated into main dashboard
    Route::get('/system-health', [App\Http\Controllers\Admin\DashboardController::class, 'systemHealth'])->name('system-health');
    Route::get('/export-data', [App\Http\Controllers\Admin\DashboardController::class, 'exportData'])->name('export-data');
    Route::get('/reports', [App\Http\Controllers\Admin\DashboardController::class, 'reports'])->name('reports');

    // User Management CRUD
    Route::resource('users-crud', App\Http\Controllers\Admin\UserController::class)->names([
        'index' => 'users-crud.index',
        'create' => 'users-crud.create',
        'store' => 'users-crud.store',
        'show' => 'users-crud.show',
        'edit' => 'users-crud.edit',
        'update' => 'users-crud.update',
        'destroy' => 'users-crud.destroy',
    ]);
    Route::patch('users-crud/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users-crud.toggle-status');

    // Subject Management CRUD
    Route::resource('subjects', App\Http\Controllers\Admin\SubjectController::class);
    Route::get('subjects/{subject}/statistics', [App\Http\Controllers\Admin\SubjectController::class, 'statistics'])->name('subjects.statistics');

    // Note Management CRUD
    Route::resource('notes-crud', App\Http\Controllers\Admin\NoteController::class)->names([
        'index' => 'notes-crud.index',
        'create' => 'notes-crud.create',
        'store' => 'notes-crud.store',
        'show' => 'notes-crud.show',
        'edit' => 'notes-crud.edit',
        'update' => 'notes-crud.update',
        'destroy' => 'notes-crud.destroy',
    ]);
    Route::patch('notes-crud/bulk-update-status', [App\Http\Controllers\Admin\NoteController::class, 'bulkUpdateStatus'])->name('notes-crud.bulk-update-status');

    // Question Management CRUD
    Route::resource('questions', App\Http\Controllers\Admin\QuestionController::class);

    // Answer Management CRUD
    Route::resource('answers', App\Http\Controllers\Admin\AnswerController::class);
    Route::patch('answers/{answer}/toggle-correctness', [App\Http\Controllers\Admin\AnswerController::class, 'toggleCorrectness'])->name('answers.toggle-correctness');
    Route::patch('answers/bulk-update-correctness', [App\Http\Controllers\Admin\AnswerController::class, 'bulkUpdateCorrectness'])->name('answers.bulk-update-correctness');

    // User Profile Management CRUD
    Route::resource('user-profiles', App\Http\Controllers\Admin\UserProfileController::class);
    Route::get('user-profiles-statistics', [App\Http\Controllers\Admin\UserProfileController::class, 'statistics'])->name('user-profiles.statistics');

    // Feedback Management CRUD
    Route::resource('feedback', App\Http\Controllers\Admin\FeedbackController::class);
    Route::get('feedback-statistics', [App\Http\Controllers\Admin\FeedbackController::class, 'statistics'])->name('feedback.statistics');
    Route::delete('feedback/bulk-delete', [App\Http\Controllers\Admin\FeedbackController::class, 'bulkDelete'])->name('feedback.bulk-delete');
});
