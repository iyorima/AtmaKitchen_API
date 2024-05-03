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
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $storeData = $request->all();

        // 📃 Validator
        // Docs: 'email' using unique exclude deleted rows. References: https://stackoverflow.com/questions/23374995/check-if-name-is-unique-among-non-deleted-items-with-laravel-validation
        $validate = Validator::make(
            $storeData,
            [
                'email' => 'required|email:rfc,dns|unique:akuns,email,NULL,id_akun,deleted_at,NULL',
                'password' => 'required|min:6',
                'nama' => 'required',
                'telepon' => 'required',
                'gaji_harian' => 'required',
                'bonus' => 'nullable',
                'alamat' => 'required',
                'id_role' => 'required',
            ],
            // Custom messages. References: https://stackoverflow.com/questions/45007905/custom-laravel-validation-messages
            [
                'email.unique' => ':attribute sudah digunakan!',
                'password.min' => ":attribute minimal 6 karakter"
            ]
        );

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        // 📃 Create akun and karyawan
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

        // 📃 Validator
        // Docs: 'email' => 'unique:table,email_column_to_check,id_to_ignore,custom_column_to_ignore'
        $validator = Validator::make($storeData, [
            'id_akun' => 'required|int',
            'email' => 'required|email:rfc,dns|unique:akuns,email,' . $storeData['id_akun'] . ',id_akun,deleted_at,NULL',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        // 📃 Find the karyawan from id (from path)
        $karyawan = Karyawan::with('akun')->find($id);

        if (!$karyawan) {
            return response(['message' => 'Karyawan tidak ditemukan'], 404);
        }

        $akun = $karyawan->akun;

        $updateData = [
            'id_role' => $request->id_role ?? $akun->id_role,
        ];

        if ($request->password) {
            $updateData['password'] = bcrypt($request->password);
        }
        $akun->update($updateData);

        $karyawan->update([
            'nama' => $request->nama ?? $karyawan->nama,
            'gaji_harian' => $request->gaji_harian ?? $karyawan->gaji_harian,
            'bonus' => $request->bonus == "" ? null : $request->bonus,
            'alamat' => $request->alamat ?? $karyawan->alamat,
            'telepon' => $request->telepon ?? $karyawan->telepon
        ]);

        // 📃 Return the response
        return response([
            'message' => 'Karyawan berhasil diubah',
            'data' => $karyawan
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

        $akun = Akun::find($karyawan->id_akun);

        if (is_null($akun)) {
            return response([
                'message' => 'Akun tidak ditemukan',
                'data' => null
            ], 404);
        }

        if ($karyawan->delete() && $akun->delete()) {
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
