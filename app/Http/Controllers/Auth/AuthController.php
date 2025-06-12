<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // Registration
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'role' defaultnya 'user' dari migrasi
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('/'); // Redirect ke halaman utama setelah registrasi
    }

    // Login
    public function showLoginForm()
    {
        // Pastikan Anda memiliki view di 'resources/views/auth/login.blade.php'
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->isAdmin()) { // Memeriksa apakah pengguna adalah admin
                return redirect()->route('admin.dashboard'); // Mengarahkan ke dashboard admin
            }
            return redirect()->intended('/'); // Mengarahkan ke halaman utama untuk user biasa
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // Profile Management
    public function showProfileForm()
    {
        return view('auth.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('profile.show')->with('status', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::min(8), 'confirmed'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.show')->with('status', 'Password updated successfully!');
    }
}