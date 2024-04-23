<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\OTP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function sendOTP(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|exists:akuns,email',
        ]);

        $user = Akun::where('email', $validatedData['email'])->first();
        return response()->json(['data' => $user]);
        $otp = OTP::generate($user->id);

        Mail::send('emails.otp', ['otp' => $otp->code], function ($message) use ($user) {
            $message->to($user->email)->subject('Reset Password OTP');
        });

        return response()->json(['message' => 'OTP telah dikirim ke email Anda.']);
    }

    public function verifyOTP(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
        ]);

        $user = Akun::where('email', $validatedData['email'])->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        }

        $otp = OTP::where('user_id', $user->id)
                  ->where('code', $validatedData['otp'])
                  ->where('expires_at', '>=', now())
                  ->first();

        if (!$otp) {
            return response()->json(['message' => 'OTP tidak valid atau sudah kedaluwarsa.'], 400);
        }

        return response()->json(['message' => 'OTP valid.']);
    }

    public function resetPassword(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
            'password' => 'required|min:8',
        ]);

        $user = Akun::where('email', $validatedData['email'])->first();
        $user->password = bcrypt($validatedData['password']);
        $user->save();

        return response()->json(['message' => 'Password berhasil direset.']);
    }
}
