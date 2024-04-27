<?php

namespace App\Http\Controllers;

use App\Models\PenarikanSaldo;
use App\Http\Requests\StorePenarikanSaldoRequest;
use App\Http\Requests\UpdatePenarikanSaldoRequest;

class PenarikanSaldoController extends Controller
{
    /**
     * Menampilkan daftar pengajuan penarikan saldo.
     */
    public function index()
    {
        $penarikanSaldo = PenarikanSaldo::all();
        return response()->json($penarikanSaldo);
    }

    /**
     * Menyimpan pengajuan penarikan saldo baru.
     */
    public function store(StorePenarikanSaldoRequest $request)
    {
        $penarikanSaldo = new PenarikanSaldo();
        $penarikanSaldo->fill($request->validated());
        $penarikanSaldo->save();

        return response()->json(['message' => 'Pengajuan penarikan saldo berhasil disimpan'], 201);
    }

    /**
     * Menampilkan detail pengajuan penarikan saldo berdasarkan ID.
     */
    public function show(PenarikanSaldo $penarikanSaldo)
    {
        return response()->json($penarikanSaldo);
    }

    /**
     * Memperbarui pengajuan penarikan saldo yang ada.
     */
    public function update(UpdatePenarikanSaldoRequest $request, PenarikanSaldo $penarikanSaldo)
    {
        $penarikanSaldo->update($request->validated());
        return response()->json(['message' => 'Pengajuan penarikan saldo berhasil diperbarui']);
    }

    /**
     * Menghapus pengajuan penarikan saldo.
     */
    public function destroy(PenarikanSaldo $penarikanSaldo)
    {
        $penarikanSaldo->delete();
        return response()->json(['message' => 'Pengajuan penarikan saldo berhasil dihapus']);
    }
}
