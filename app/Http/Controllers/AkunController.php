<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Http\Requests\StoreAkunRequest;
use App\Http\Requests\UpdateAkunRequest;
use App\Models\Alamat;
use App\Models\Karyawan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class AkunController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
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

    public function register(Request $request)
    {
        $registrationData = $request->all();

        $validate = Validator::make($registrationData, [
            'nama' => 'required',
            'email' => 'required|email:rfc,dns|unique:akuns',
            'password' => 'required',
            'nama_alamat' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
            'tgl_lahir' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        // $str = Str::random(100);
        $registrationData['id_role'] = 1; // customer
        // $registrationData['verify_key'] = $str;
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
            'id_role' => $registrationData['id_role'],
            'profile_image' => fake()->imageUrl()
        ]);

        $pelanggan = Pelanggan::create([
            'id_akun' => $akun->id_akun,
            'nama' => $registrationData['nama'],
            'tgl_lahir' => $registrationData['tgl_lahir'],
        ]);

        $alamat = Alamat::create([
            'id_pelanggan' => $pelanggan->id_pelanggan,
            'label' => $registrationData['nama'],
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
            'token_type' => 'bearer',
            'expires_in' => $auth->factory()->getTTL() * 60
        ])->withCookie($cookie);
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
