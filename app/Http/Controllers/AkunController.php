<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Http\Requests\StoreAkunRequest;
use App\Http\Requests\UpdateAkunRequest;
use App\Mail\MailSend;
use App\Models\Alamat;
use App\Models\Karyawan;
use App\Models\OTP;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AkunController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'sendOTP', 'verifyOTP', 'resetPassword']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Email atau password salah!'], 401);
        }

        $user = Akun::where('email', $credentials['email'])->first();
        if (!$user || !$user->email_verified_at) {
            return response()->json(['error' => 'Email belum diverifikasi!'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $akun = auth()->user();

        $user = Pelanggan::with('akun.role', 'poins:id_poin,total_poin,poins.id_pelanggan')->where('id_akun', $akun->id_akun)->first();

        if (is_null($user)) {
            $user = Karyawan::with('akun.role')->where('id_akun', $akun->id_akun)->first();
        }
        return response()->json(['data' => $user]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $cookie = Cookie::forget('token');

        auth()->logout();

        return response()->json(['message' => 'Successfully logged out'])->withCookie($cookie);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        /** @var Illuminate\Auth\AuthManager */
        $auth = auth();
        return $this->respondWithToken($auth->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        /** @var Illuminate\Auth\AuthManager */
        $auth = auth();
        $cookie = cookie('token', $token, $auth->factory()->getTTL() * 60);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => $auth->factory()->getTTL() * 60
        ])->withCookie($cookie);
    }

    /**
     * Store new customer.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $registrationData = $request->all();

        $validate = Validator::make($registrationData, [
            'nama' => 'required',
            'email' => 'required|email:rfc,dns|unique:akuns',
            'password' => 'required',
            'tgl_lahir' => 'required',
            'nama_alamat' => 'required',
            'alamat' => 'required',
            'telepon' => 'required'
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        // $str = Str::random(100);
        $registrationData['id_role'] = 1; // customer
        // $registrationData['verify_key'] = $str;
        $registrationData['password'] = bcrypt($request->password);


        // Mail::to($request->email)->send(new Mailsend($details));
        $akun = Akun::create([
            'email' => $registrationData['email'],
            'password' => $registrationData['password'],
            'id_role' => $registrationData['id_role'],

        ]);

        $pelanggan = Pelanggan::create([
            'id_akun' => $akun->id_akun,
            'nama' => $registrationData['nama'],
            'tgl_lahir' => $registrationData['tgl_lahir'],
            'telepon' => $registrationData['telepon'],
        ]);

        $alamat = Alamat::create([
            'id_pelanggan' => $pelanggan->id_pelanggan,
            'label' => $registrationData['nama_alamat'],
            'nama' => $pelanggan->nama,
            'alamat' => $registrationData['alamat'],
            'tgl_lahir' => $registrationData['tgl_lahir'],
            'telepon' => $registrationData['telepon']
        ]);

        return response([
            'message' => 'Akun baru berhasil dibuat. Verifikasi kode OTP sekarang.',
            'data' => [
                'akun' => $akun,
                'pelanggan' => $pelanggan,
                'alamat' => $alamat,
            ]
        ], 200);
    }

    /**
     * Send OTP Code for email verification.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOTP(Request $request)
    {
        $requestData = $request->all();

        $validate = Validator::make($requestData, [
            'email' => 'required|email|exists:akuns,email',
        ], ['email.exists' => "Email tidak ditemukan!"]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $user = Akun::where('email', $requestData['email'])->first();


        if (!$user) {
            return response()->json([
                'error' => "Email tidak ditemukan!"
            ], 400);
        }

        $otp = OTP::generate($user->id_akun);

        $details = [
            'title' => 'Lupa Password AtmaKitchen',
            'otp' => $otp->code
        ];

        Mail::to($request->email)->send(new MailSend($details));

        return response()->json(['message' => 'OTP telah dikirim ke email Anda.']);
    }

    /**
     * Verify email using OTP
     *
     * @return \Illuminate\Http\JsonResponse
     */
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

        $user->email_verified_at = now();
        $user->save();

        return response()->json(['message' => 'Email berhasil diverifikasi.'], 200);
    }

    public function resetPassword(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email|exists:akuns,email',
                'otp' => 'required|digits:4',
                'password' => 'required',
            ], [
                'email.exists' => 'Email tidak ditemukan!',
                'email.required' => 'Email wajib diisi!',
                'email.email' => 'Email tidak valid!',
                'otp.required' => 'OTP wajib diisi!',
                'otp.digits' => 'OTP harus terdiri dari 4 angka!',
                'password.required' => 'Password wajib diisi!',
            ]);

            // Fetch user after verification succeeds
            $user = Akun::where('email', $validatedData['email'])->first();

            $otp = OTP::where('user_id', $user->id_akun)
                ->where('code', $validatedData['otp'])
                ->where('expires_at', '>=', now())
                ->first();

            if (!$otp) {
                return response()->json(['message' => 'OTP tidak valid atau sudah kedaluwarsa.'], 400);
            }

            // Update user password securely
            $user->password = bcrypt($validatedData['password']);
            $user->save();

            return response()->json(['message' => 'Password berhasil direset.']);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error resetting password. Please try again later.'], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAkunRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Akun $akun)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Akun $akun)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAkunRequest $request, Akun $akun)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Akun $akun)
    {
        //
    }
}
