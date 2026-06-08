<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Student;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $usernameInput = $credentials['username'];
        $passwordInput = $credentials['password'];

        // 1. Try standard login (username)
        if (Auth::attempt(['username' => $usernameInput, 'password' => $passwordInput, 'is_active' => 1])) {
            $request->session()->regenerate();
            return $this->redirectBasedOnRole(Auth::user());
        }

        // 2. Try parent login via student NISN
        $student = Student::where('nis', $usernameInput)->first();
        if ($student && $student->guardian_id) {
            $guardianUser = $student->guardian->user;
            if ($guardianUser && $guardianUser->is_active) {
                // Verify password against guardian's user record
                if (Auth::attempt(['username' => $guardianUser->username, 'password' => $passwordInput])) {
                    $request->session()->regenerate();
                    // We might want to store the selected child in session for context
                    session(['active_student_id' => $student->id]);
                    return $this->redirectBasedOnRole(Auth::user());
                }
            }
        }

        return back()->withErrors([
            'username' => 'Username/NISN atau Password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    protected function redirectBasedOnRole($user)
    {
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'teacher' => redirect()->route('teacher.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            'parent' => redirect()->route('parent.dashboard'),
            default => redirect('/'),
        };
    }
}
