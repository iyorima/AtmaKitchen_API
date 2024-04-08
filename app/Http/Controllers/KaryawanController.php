<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $karyawan = Karyawan::with('akun.role')->get();

        if (count($karyawan) > 0) {
            return response([
                "message" => "Berhasil mendapatkan semua karyawan",
                "data" => $karyawan
            ], 200);
        }

        return response([
            "message" => "Karyawan tidak tersedia",
            "data" => $karyawan
        ], 400);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'email' => 'required',
            'password' => 'required',
            'nama' => 'required',
            'telepon' => 'required',
            'gaji_harian' => 'required',
            'alamat' => 'required',
            'id_role' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $akun = Akun::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'id_role' => $request->id_role
        ]);

        $karyawan = Karyawan::create([
            'id_akun' => $akun->id_akun,
            'nama' => $request->nama,
            'gaji_harian' => $request->gaji_harian,
            'bonus' => $request->bonus,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon
        ]);

        return response([
            'message' => 'Karyawan berhasil ditambahkan',
            'data' => $karyawan
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $karyawan = Karyawan::with('akun.role')->find($id);

        if (!is_null($karyawan)) {
            $data = $karyawan->only([
                'id_karyawan',
                'nama',
                'gaji_harian',
                'bonus',
                'alamat',
                'telepon',
                'created_at',
            ]);

            $data['role'] = $karyawan->akun->role->role;

            return response([
                'message' => 'karyawan ditemukan',
                'data' => $data,
            ], 200);
        }

        return response([
            'message' => 'karyawan tidak tersedia',
            'data' => null,
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Karyawan $karyawan)
    {
        //
    }
}
