<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\MailSend;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $registrationData = $request->all();

        $validate = Validator::make($registrationData, [
            'nama' => 'required',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required',
            'tanggal_lahir' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $str = Str::random(100);
        $registrationData['id_role'] = 4;
        $registrationData['verify_key'] = $str;
        // TODO: aktifkan is_verified
        $registrationData['is_verified'] = 1;
        $registrationData['password'] = bcrypt($request->password);

        // $details = [
        //     'nama' => $request->name,
        //     'website' => 'atmakitchen',
        //     'datetime' => date('Y-m-d H:i:s'),
        //     'url' => request()->getHttpHost() . '/api/register/verify/' . $str
        // ];

        // Mail::to($request->email)->send(new Mailsend($details));
        $user = User::create($registrationData);

        return response([
            'message' => 'Registrasi Berhasil, silahkan verifikasi akun',
            'data' => $user
        ], 200);
    }

    public function verify($verify_key)
    {
        $user = User::where('verify_key', $verify_key)->first();

        if ($user) {
            $user->update([
                'is_verified' => 1,
                'email_verified_at' => now(),
            ]);

            return "Verifikasi akun berhasil! Akunmu sudah aktif.";
        } else {
            return "Invalid key.";
        }
    }

    public function login(Request $request)
    {
        $loginData = $request->all();

        $validate = Validator::make($loginData, [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        if (!Auth::attempt($loginData)) {
            return response(['message' => 'Email atau password salah!'], 401);
        }

        /** @var \App\Models\User $user **/
        $user = Auth::user();
        if ($user->is_verified == 1) {
            $token = $user->createToken('Authentication Token')->accessToken;

            return response([
                'message' => 'Berhasil login',
                'user' => $user,
                'token_type' => 'Bearer',
                'access_token' => $token
            ], 200);
        } else {
            return response([
                'message' => 'Akun belum diverifikasi, silahkan cek email anda',
            ], 401);
        }
    }
}
