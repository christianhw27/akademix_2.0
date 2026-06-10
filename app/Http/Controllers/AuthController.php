<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Student;

/**
 * Controller untuk mengelola otentikasi pengguna (Login umum dan Login Orang Tua).
 */
class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     *
     * @return \Illuminate\View\View
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Memproses permintaan masuk (login) untuk semua role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $loginType = $request->input('login_type', 'standard');

        if ($loginType === 'parent') {
            $request->validate([
                'nis' => 'required|string',
                'student_username' => 'required|string',
            ]);

            $student = Student::where('nis', $request->nis)->first();
            if ($student && $student->user && $student->user->username === $request->student_username) {
                if (!$student->user->is_active) {
                    return back()->withErrors([
                        'nis' => 'Akun siswa tidak aktif.',
                    ])->onlyInput('nis');
                }
                
                Auth::login($student->user);
                $request->session()->regenerate();
                
                session([
                    'is_parent' => true,
                    'active_student_id' => $student->id
                ]);

                return redirect()->route('parent.dashboard');
            }

            return back()->withErrors([
                'nis' => 'NIS atau Username Siswa salah.',
            ])->onlyInput('nis');
        }

        // Standard Login Flow
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $usernameInput = $credentials['username'];
        $passwordInput = $credentials['password'];

        if (Auth::attempt(['username' => $usernameInput, 'password' => $passwordInput, 'is_active' => 1])) {
            $request->session()->regenerate();
            $request->session()->forget('is_parent');
            
            return $this->redirectBasedOnRole(Auth::user());
        }

        return back()->withErrors([
            'username' => 'Username atau Password salah.',
        ])->onlyInput('username');
    }

    /**
     * Memproses keluar log (logout) pengguna.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    /**
     * Mengarahkan pengguna ke dashboard yang sesuai berdasarkan role mereka.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
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
