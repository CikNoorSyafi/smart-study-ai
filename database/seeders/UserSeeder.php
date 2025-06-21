<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $adminUser = User::create([
            'user_id' => \Illuminate\Support\Str::uuid(),
            'username' => 'admin_user',
            'name' => 'Admin User',
            'email' => 'admin@smartstudy.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
            'google_id' => null,
            'avatar' => null,
            'email_verified_at' => now(),
        ]);

        // Create demo users for testing
        $demoUser = User::create([
            'user_id' => \Illuminate\Support\Str::uuid(),
            'username' => 'demo_user',
            'name' => 'Demo User',
            'email' => 'demo@smartstudy.com',
            'password' => Hash::make('demo123'),
            'role' => 'student',
            'is_active' => true,
            'google_id' => null,
            'avatar' => null,
            'email_verified_at' => now(),
        ]);

        $testUser = User::create([
            'user_id' => \Illuminate\Support\Str::uuid(),
            'username' => 'test_user',
            'name' => 'Test User',
            'email' => 'test@smartstudy.com',
            'password' => Hash::make('test123'),
            'role' => 'student',
            'is_active' => true,
            'google_id' => null,
            'avatar' => null,
            'email_verified_at' => now(),
        ]);

        $johnUser = User::create([
            'user_id' => \Illuminate\Support\Str::uuid(),
            'username' => 'john_smith',
            'name' => 'John Smith',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'role' => 'teacher',
            'is_active' => true,
            'google_id' => null,
            'avatar' => null,
            'email_verified_at' => now(),
        ]);

        $sarahUser = User::create([
            'user_id' => \Illuminate\Support\Str::uuid(),
            'username' => 'sarah_johnson',
            'name' => 'Sarah Johnson',
            'email' => 'sarah@example.com',
            'password' => Hash::make('password123'),
            'role' => 'teacher',
            'is_active' => true,
            'google_id' => null,
            'avatar' => null,
            'email_verified_at' => now(),
        ]);

        $mikeUser = User::create([
            'user_id' => \Illuminate\Support\Str::uuid(),
            'username' => 'mike_wilson',
            'name' => 'Mike Wilson',
            'email' => 'mike@example.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'is_active' => true,
            'google_id' => null,
            'avatar' => null,
            'email_verified_at' => now(),
        ]);

        $emilyUser = User::create([
            'user_id' => \Illuminate\Support\Str::uuid(),
            'username' => 'emily_davis',
            'name' => 'Emily Davis',
            'email' => 'emily@example.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'is_active' => true,
            'google_id' => null,
            'avatar' => null,
            'email_verified_at' => now(),
        ]);

        $tomUser = User::create([
            'user_id' => \Illuminate\Support\Str::uuid(),
            'username' => 'tom_garcia',
            'name' => 'Tom Garcia',
            'email' => 'tom@example.com',
            'password' => Hash::make('password123'),
            'role' => 'parent',
            'is_active' => true,
            'google_id' => null,
            'avatar' => null,
            'email_verified_at' => now(),
        ]);

        // Create additional users using factory
        User::factory(20)->create();
    }
}
