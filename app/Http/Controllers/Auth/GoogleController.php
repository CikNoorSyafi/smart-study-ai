<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Redirect to Google OAuth provider
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            // Get user info from Google
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists with this Google ID
            $existingUser = User::where('google_id', $googleUser->getId())->first();

            // Check if user exists with this email
            if ($existingUser) {
                // User exists with Google ID, log them in
                Auth::login($existingUser);
                return $this->redirectAfterLogin($existingUser);
            }
            
            // Check if user exists with this email
            $existingEmailUser = User::where('email', $googleUser->getEmail())->first();
            
            if ($existingEmailUser) {
                // User exists with email, link Google account
                $existingEmailUser->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                ]);
                
                Auth::login($existingEmailUser);
                return $this->redirectAfterLogin($existingEmailUser);
            }
            
            // Create new user
            $newUser = $this->createNewUser($googleUser);
            Auth::login($newUser);
            
            return $this->redirectAfterLogin($newUser);
            
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'Connection refused') ||
                str_contains($e->getMessage(), 'could not find driver') ||
                str_contains($e->getMessage(), 'No connection could be made')) {
                return redirect('/login')->with('error', 'Database connection failed. Please start MySQL service.');
            }
            return redirect('/login')->with('error', 'Google authentication failed. Please try again.');
        }
    }

    /**
     * Create a new user from Google data
     */
    private function createNewUser($googleUser)
    {
        // Generate unique username
        $baseUsername = $this->generateUsername($googleUser->getName());
        $username = $this->ensureUniqueUsername($baseUsername);
        
        return User::create([
            'user_id' => (string) Str::uuid(),
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'username' => $username,
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
            'email_verified_at' => now(),
            'password' => Hash::make(Str::random(24)), // Random password
            'role' => 'student', // Default role
            'is_active' => true,
        ]);
    }

    /**
     * Generate username from name
     */
    private function generateUsername($name)
    {
        // Remove special characters and convert to lowercase
        $username = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $name));
        
        // Ensure minimum length
        if (strlen($username) < 3) {
            $username = 'user' . $username;
        }
        
        // Ensure maximum length
        if (strlen($username) > 20) {
            $username = substr($username, 0, 20);
        }
        
        return $username;
    }

    /**
     * Ensure username is unique
     */
    private function ensureUniqueUsername($baseUsername)
    {
        $username = $baseUsername;
        $counter = 1;
        
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        return $username;
    }

    /**
     * Redirect user after successful login based on role
     */
    private function redirectAfterLogin($user)
    {
        // Store user data in session
        session([
            'user' => [
                'id' => $user->user_id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'avatar' => $user->avatar,
            ]
        ]);

        // Redirect based on role
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
            case 'teacher':
                return redirect()->route('teacher.dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
            case 'parent':
                return redirect()->route('parent.dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
            default:
                return redirect()->route('dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
        }
    }

    /**
     * Link Google account to existing user
     */
    public function linkGoogleAccount(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please login first to link your Google account.');
        }

        try {
            $googleUser = Socialite::driver('google')->user();
            $currentUser = Auth::user();

            // Check if Google account is already linked to another user
            $existingGoogleUser = User::where('google_id', $googleUser->getId())
                                    ->where('user_id', '!=', $currentUser->user_id)
                                    ->first();

            if ($existingGoogleUser) {
                return redirect()->back()->with('error', 'This Google account is already linked to another user.');
            }

            // Link Google account to current user
            $currentUser->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]);

            return redirect()->back()->with('success', 'Google account linked successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to link Google account. Please try again.');
        }
    }

    /**
     * Unlink Google account from user
     */
    public function unlinkGoogleAccount()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        
        if (!$user->google_id) {
            return redirect()->back()->with('error', 'No Google account is linked to your profile.');
        }

        $user->update([
            'google_id' => null,
        ]);

        return redirect()->back()->with('success', 'Google account unlinked successfully!');
    }
}
