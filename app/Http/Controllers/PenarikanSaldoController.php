<?php

namespace App\Http\Controllers;

use App\Models\PenarikanSaldo;
use App\Models\SaldoPelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class PenarikanSaldoController extends Controller
{
    /**
     * Menampilkan daftar pengajuan penarikan saldo.
     */
    public function index()
    {
        $penarikanSaldo = PenarikanSaldo::all();
    
        if ($penarikanSaldo->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada data pengajuan penarikan saldo',
                'data' => []
            ], 404);
        }
    
        return response()->json([
            'message' => 'Berhasil mendapatkan seluruh data pengajuan penarikan saldo',
            'data' => $penarikanSaldo
        ]);
    }
    

    /**
     * Menyimpan pengajuan penarikan saldo baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_akun' => 'required|exists:akuns,id_akun',
            'jumlah_penarikan' => 'required|numeric|min:0',
            'nama_bank' => ['required', Rule::in(['bca', 'mandiri'])],
            'nomor_rekening' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $data = $request->all();
        $data['status'] = 'menunggu';

        $penarikanSaldo=PenarikanSaldo::create($data);
        return response([
            'message' => 'Berhasil menambahkan data penarikan saldo',
            'data' => $penarikanSaldo
        ], 200);
    }

    public function update(Request $request, int $id)
{
    // Validasi data yang diterima dari request
    $validator = Validator::make($request->all(), [
        'status' => ['required', Rule::in(['selesai', 'ditolak'])],
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    $penarikanSaldo = PenarikanSaldo::findOrFail($id);

    // Jika status menjadi "selesai", lakukan validasi saldo dan jumlah penarikan
    if ($request->status === 'selesai') {
        $saldoPelanggan = SaldoPelanggan::where('id_akun', $penarikanSaldo->id_akun)->first();
        if (!$saldoPelanggan) {
            return response()->json(['error' => 'Saldo pelanggan tidak ditemukan'], 404);
        }

        // Jika jumlah penarikan melebihi saldo, kembalikan pesan error
        if ($penarikanSaldo->jumlah_penarikan > $saldoPelanggan->saldo) {
            return response()->json(['error' => 'Jumlah penarikan melebihi saldo'], 400);
        }

        // Kurangi saldo pengguna dengan jumlah penarikan
        $saldoPelanggan->saldo -= $penarikanSaldo->jumlah_penarikan;
        $saldoPelanggan->total_saldo -= $penarikanSaldo->jumlah_penarikan;
        $saldoPelanggan->save();
    }

    // Update status pengajuan penarikan saldo
    $penarikanSaldo->status = $request->status;
    $penarikanSaldo->save();

    return response([
        'message' => 'Berhasil mengubah status penarikan saldo',
        'data' => $penarikanSaldo,
        'dataPelanggan' => $saldoPelanggan
    ], 200);
}
    public function show(int $id)
    {
        $penarikanSaldo = PenarikanSaldo::find($id);
    
        if (!$penarikanSaldo) {
            return response()->json(['message' => 'Pengajuan penarikan saldo tidak ditemukan'], 404);
        }
    
        return response([
            'message' => 'Penarikan saldo dengan id ' . $penarikanSaldo->id . ' ditemukan',
            'data' => $penarikanSaldo
        ], 200);
    }
    
    public function showByCustomer(int $id_akun)
{
    $penarikanSaldo = PenarikanSaldo::where('id_akun', $id_akun)->get();

    if ($penarikanSaldo->isEmpty()) {
        return response()->json(['message' => 'Tidak ada pengajuan penarikan saldo untuk akun ini'], 404);
    }
    return response([
        'message' => 'Penarikan saldo dengan id akun ' . $penarikanSaldo->first()->id_akun  . ' ditemukan',
        'data' => $penarikanSaldo
    ], 200);
}

}
