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
        // TODO: unique disabled when akuns with that email have been deleted
        $validate = Validator::make($storeData, [
            'email' => 'required|email:rfc,dns|unique:akuns',
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
            // $data = $karyawan->only([
            //     'id_karyawan',
            //     'nama',
            //     'gaji_harian',
            //     'bonus',
            //     'alamat',
            //     'telepon',
            //     'created_at',
            //     'akun'
            // ]);

            // $data['role'] = $karyawan->akun->role->role;

            return response([
                'message' => 'karyawan ditemukan',
                'data' => $karyawan,
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
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            // 'id_akun' => 'required|int',
            'email' => 'required|email:rfc,dns|unique:akuns,email,' . $storeData['id_akun'],
            'password' => 'nullable',
            'nama' => 'required',
            'telepon' => 'required',
            'gaji_harian' => 'required',
            'alamat' => 'required',
            'id_role' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $karyawan = Karyawan::with('akun')->find($id);

        if (!$karyawan) {
            return response(['message' => 'Karyawan tidak ditemukan'], 404);
        }

        $akun = $karyawan->akun;

        $akun->update([
            'password' => bcrypt($request->password), // Update password if provided
            'id_role' => $request->id_role
        ]);

        $karyawan->update([
            'nama' => $request->nama,
            'gaji_harian' => $request->gaji_harian,
            'bonus' => $request->bonus, // Assuming bonus is also an update field
            'alamat' => $request->alamat,
            'telepon' => $request->telepon
        ]);

        return response([
            'message' => 'Karyawan berhasil diubah',
            'data' => $karyawan->load('akun.role') // Eager load data for response
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $karyawan = Karyawan::find($id);

        if (is_null($karyawan)) {
            return response([
                'message' => 'Karyawan tidak ditemukan',
                'data' => null
            ], 404);
        }

        if ($karyawan->delete()) {
            return response([
                'message' => 'Karyawan berhasil dihapus',
                'data' => $karyawan
            ], 200);
        }

        return response([
            'message' => 'Karyawan gagal dihapus',
            'data' => null
        ], 400);
    }
}
