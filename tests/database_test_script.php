<?php

/**
 * Database Authentication Testing Script
 * 
 * This script tests the database connectivity and authentication functionality
 * Run this script from the Laravel root directory using: php tests/database_test_script.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

class DatabaseAuthTest
{
    private $testResults = [];
    private $testCount = 0;
    private $passCount = 0;

    public function runAllTests()
    {
        echo "=== Database Authentication Test Suite ===\n\n";
        
        $this->testDatabaseConnection();
        $this->testUserTableExists();
        $this->testUserCreation();
        $this->testUserAuthentication();
        $this->testPasswordHashing();
        $this->testUserRetrieval();
        $this->testUserUpdate();
        $this->testUserDeletion();
        $this->testSeededUsers();
        
        $this->printResults();
    }

    private function test($testName, $callback)
    {
        $this->testCount++;
        echo "Running: {$testName}... ";
        
        try {
            $result = $callback();
            if ($result) {
                echo "PASS\n";
                $this->passCount++;
                $this->testResults[] = ['name' => $testName, 'status' => 'PASS', 'message' => ''];
            } else {
                echo "FAIL\n";
                $this->testResults[] = ['name' => $testName, 'status' => 'FAIL', 'message' => 'Test returned false'];
            }
        } catch (Exception $e) {
            echo "FAIL - " . $e->getMessage() . "\n";
            $this->testResults[] = ['name' => $testName, 'status' => 'FAIL', 'message' => $e->getMessage()];
        }
    }

    private function testDatabaseConnection()
    {
        $this->test('Database Connection', function() {
            DB::connection()->getPdo();
            return true;
        });
    }

    private function testUserTableExists()
    {
        $this->test('Users Table Exists', function() {
            return DB::getSchemaBuilder()->hasTable('users');
        });
    }

    private function testUserCreation()
    {
        $this->test('User Creation', function() {
            $user = User::create([
                'name' => 'Test User ' . time(),
                'email' => 'test' . time() . '@example.com',
                'password' => Hash::make('password123'),
            ]);
            
            return $user->id > 0;
        });
    }

    private function testUserAuthentication()
    {
        $this->test('User Authentication', function() {
            $email = 'auth_test' . time() . '@example.com';
            $password = 'password123';
            
            // Create user
            $user = User::create([
                'name' => 'Auth Test User',
                'email' => $email,
                'password' => Hash::make($password),
            ]);
            
            // Test authentication
            $credentials = ['email' => $email, 'password' => $password];
            return auth()->attempt($credentials);
        });
    }

    private function testPasswordHashing()
    {
        $this->test('Password Hashing', function() {
            $password = 'testpassword123';
            $hashedPassword = Hash::make($password);
            
            // Check that password is hashed (not plain text)
            $isHashed = $hashedPassword !== $password;
            
            // Check that hash can be verified
            $canVerify = Hash::check($password, $hashedPassword);
            
            return $isHashed && $canVerify;
        });
    }

    private function testUserRetrieval()
    {
        $this->test('User Retrieval', function() {
            // Get first user
            $user = User::first();
            
            if (!$user) {
                return false;
            }
            
            // Test retrieval by email
            $userByEmail = User::where('email', $user->email)->first();
            
            return $userByEmail && $userByEmail->id === $user->id;
        });
    }

    private function testUserUpdate()
    {
        $this->test('User Update', function() {
            $user = User::create([
                'name' => 'Update Test User',
                'email' => 'update_test' . time() . '@example.com',
                'password' => Hash::make('password123'),
            ]);
            
            $newName = 'Updated Name ' . time();
            $user->update(['name' => $newName]);
            
            $updatedUser = User::find($user->id);
            return $updatedUser->name === $newName;
        });
    }

    private function testUserDeletion()
    {
        $this->test('User Deletion', function() {
            $user = User::create([
                'name' => 'Delete Test User',
                'email' => 'delete_test' . time() . '@example.com',
                'password' => Hash::make('password123'),
            ]);
            
            $userId = $user->id;
            $user->delete();
            
            $deletedUser = User::find($userId);
            return $deletedUser === null;
        });
    }

    private function testSeededUsers()
    {
        $this->test('Seeded Users Exist', function() {
            $adminUser = User::where('email', 'admin@smartstudy.com')->first();
            $demoUser = User::where('email', 'demo@smartstudy.com')->first();
            $testUser = User::where('email', 'test@smartstudy.com')->first();
            
            return $adminUser && $demoUser && $testUser;
        });
    }

    private function printResults()
    {
        echo "\n=== Test Results ===\n";
        echo "Total Tests: {$this->testCount}\n";
        echo "Passed: {$this->passCount}\n";
        echo "Failed: " . ($this->testCount - $this->passCount) . "\n";
        echo "Success Rate: " . round(($this->passCount / $this->testCount) * 100, 2) . "%\n\n";
        
        if ($this->passCount < $this->testCount) {
            echo "Failed Tests:\n";
            foreach ($this->testResults as $result) {
                if ($result['status'] === 'FAIL') {
                    echo "- {$result['name']}: {$result['message']}\n";
                }
            }
        }
        
        echo "\n=== Database Statistics ===\n";
        try {
            $userCount = User::count();
            echo "Total Users in Database: {$userCount}\n";
            
            $recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();
            echo "\nRecent Users:\n";
            foreach ($recentUsers as $user) {
                echo "- {$user->name} ({$user->email}) - {$user->created_at}\n";
            }
            
        } catch (Exception $e) {
            echo "Error retrieving database statistics: " . $e->getMessage() . "\n";
        }
    }
}

// Run the tests
$tester = new DatabaseAuthTest();
$tester->runAllTests();

echo "\n=== Manual Test Credentials ===\n";
echo "Use these credentials for manual testing:\n";
echo "Admin: admin@smartstudy.com / password123\n";
echo "Demo: demo@smartstudy.com / demo123\n";
echo "Test: test@smartstudy.com / test123\n";
echo "Others: john@example.com / password123\n";
echo "\n=== Test URLs ===\n";
echo "Login: http://127.0.0.1:8000/login\n";
echo "Register: http://127.0.0.1:8000/register\n";
echo "Dashboard: http://127.0.0.1:8000/dashboard\n";
echo "Admin: http://127.0.0.1:8000/admin\n";
