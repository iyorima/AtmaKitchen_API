<?php

namespace App\Http\Controllers;

use App\Mail\MailSend;
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

        $otp = OTP::generate($user->id_akun);

        $details = [
            'title' => 'Lupa Password AtmaKitchen',
            'otp' => $otp->code
        ];

        Mail::to($request->email)->send(new MailSend($details));

        return response()->json(['message' => 'OTP telah dikirim ke email Anda.']);
    }

    public function verifyOTP(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|exists:akuns,email',
            'otp' => 'required|digits:4',
        ]);

        $user = Akun::where('email', $validatedData['email'])->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        }

        $otp = OTP::where('user_id', $user->id_akun)
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
            'otp' => 'required|digits:4',
            'password' => 'required|min:8',
        ]);

        $user = Akun::where('email', $validatedData['email'])->first();
        $user->password = bcrypt($validatedData['password']);
        $user->save();

        return response()->json(['message' => 'Password berhasil direset.']);
    }
}