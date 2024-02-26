<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;

class roleController extends Controller
{
    // menampilkan seluruh role
    public function index()
    {
        $role = Role::all();

        if (count($role) > 0) {
            return response([
                'message' => 'Berhasil Mendapatkan semua role.',
                'data' => $role
            ], 200);
        }

        return response([
            'message' => 'Role masih kosong',
            'data' => null
        ], 400);
    }

    // membuat role baru
    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'role' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $role = Role::create($storeData);
        return response([
            'message' => 'Role berhasil dibuat',
            'data' => $role
        ], 200);
    }

    // Menampilkan role berdasarkan id
    public function show($id)
    {
        $role = Role::find($id);

        if (!is_null($role)) {
            return response([
                'message' => 'role ditemukan',
                'data' => $role
            ], 200);
        }

        return response([
            'message' => 'role tidak ditemukan',
            'data' => null
        ], 404);
    }

    // Ubah role
    public function update(Request $request, string $id)
    {
        $role = Role::find($id);
        if (is_null($role)) {
            return response([
                'message' => 'role tidak ditemukan',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'role' => 'required',
        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }

        $role->role = $updateData['role'];

        if ($role->save()) {
            return response([
                'message' => 'Role berhasil diubah',
                'data' => $role
            ], 200);
        }

        return response([
            'message' => 'Ubah role gagal',
            'data' => null
        ], 400);
    }

    // menghapus role
    public function destroy(string $id)
    {
        $role = Role::find($id);

        if (is_null($role)) {
            return response([
                'message' => 'role tidak ditemukan',
                'data' => null
            ], 404);
        }

        if ($role->delete()) {
            return response([
                'message' => 'role berhasil dihapus',
                'data' => $role
            ], 200);
        }

        return response([
            'message' => 'role gagal dihapus',
            'data' => null
        ], 400);
    }
}
