<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ChangePasswordController extends Controller
{
    /**
     * Show the change password form for first-time login
     */
    public function showChangePasswordForm()
    {
        $user = Auth::user();

        // If user has already changed password, redirect to dashboard
        if ($user->password_changed) {
            return redirect()->route('dashboard');
        }

        return view('auth.change-password');
    }

    /**
     * Handle password change for first-time login
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        // If user has already changed password, redirect to dashboard
        if ($user->password_changed) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', Password::min(8)->letters()->numbers(), 'confirmed'],
        ], [
            'current_password.required' => 'Password lama harus diisi.',
            'password.required' => 'Password baru harus diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.letters' => 'Password baru harus mengandung huruf.',
            'password.numbers' => 'Password baru harus mengandung angka.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak cocok.']);
        }

        // Update password and mark as changed
        User::where('id', $user->id)->update([
            'password' => Hash::make($request->password),
            'password_changed' => true,
            'first_login_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Password berhasil diubah! Selamat datang di sistem YAKIIN.');
    }
}
