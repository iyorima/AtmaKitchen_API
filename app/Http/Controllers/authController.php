<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Akun;
use App\Models\Pelanggan;
use App\Models\Alamat;
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
            'email' => 'required|email:rfc,dns|unique:akuns',
            'password' => 'required',
            'nama_alamat' => 'required',
            'alamat' => 'required',
            'telepon' => 'required'
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $str = Str::random(100);
        $registrationData['id_role'] = 1; // customer
        $registrationData['verify_key'] = $str;
        $registrationData['password'] = bcrypt($request->password);

        // $details = [
        //     'nama' => $request->name,
        //     'website' => 'atmakitchen',
        //     'datetime' => date('Y-m-d H:i:s'),
        //     'url' => request()->getHttpHost() . '/api/register/verify/' . $str
        // ];

        // Mail::to($request->email)->send(new Mailsend($details));
        $akun = Akun::create([
            'email' => $registrationData['email'],
            'password' => $registrationData['password'],
            'id_role' => $registrationData['id_role']
        ]);

        $pelanggan = Pelanggan::create([
            'id_akun' => $akun->id_akun,
            'nama' => $registrationData['nama']
        ]);

        $alamat = Alamat::create([
            'id_pelanggan' => $pelanggan->id_pelanggan,
            'nama' => $registrationData['nama_alamat'],
            'alamat' => $registrationData['alamat'],
            'telepon' => $registrationData['telepon']
        ]);

        return response([
            'message' => 'Registrasi Berhasil, silahkan verifikasi akun',
            'data' => [
                'akun' => $akun,
                'pelanggan' => $pelanggan,
                'alamat' => $alamat,
            ]
        ], 200);
    }

    public function verify($verify_key)
    {
        $akun = Akun::where('verify_key', $verify_key)->first();

        if ($akun) {
            $akun->update([
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

        /** @var \App\Models\Akun $akun **/
        $akun = Auth::user();
        $data = Akun::with('role')->where('id_role', '=', $akun->id_role)->get();
        // TODO: fix verifikasi email
        if ($akun->email_verified_at == null) {
            $token = $akun->createToken('Authentication Token')->accessToken;

            return response([
                'message' => 'Berhasil login',
                'data' => [
                    'akun' => $data,
                    'token_type' => 'Bearer',
                    'access_token' => $token
                ]
            ], 200);
        } else {
            return response([
                'message' => 'Akun belum diverifikasi, silahkan cek email anda',
            ], 401);
        }
    }
}
