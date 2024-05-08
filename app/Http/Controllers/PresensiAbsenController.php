<?php

namespace App\Http\Controllers;

use App\Models\PresensiAbsen;
use App\Http\Requests\StorePresensiAbsenRequest;
use App\Http\Requests\UpdatePresensiAbsenRequest;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PresensiAbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $month = $request->query('month');

        // Check if month is set in parameter 
        $currentMonth = isset($month) ? Carbon::now()->month($month) : Carbon::now();

        $absentRecords = PresensiAbsen::whereMonth('created_at', $currentMonth)
            ->with('id_karyawan')
            ->get()
            ->groupBy('id_karyawan');

        $daysInCurrentMonth = $currentMonth->daysInMonth;

        // Get all employees
        $karyawan = Karyawan::all();

        // Calculate present days for each employee
        $karyawanPresensi = [];

        foreach ($karyawan as $karyawanData) {
            $karyawanId = $karyawanData->id_karyawan;
            $absentDays = isset($absentRecords[$karyawanId]) ? $absentRecords[$karyawanId]->count() : 0;
            $presentDays = $daysInCurrentMonth - $absentDays;

            $karyawanPresensi[] = [
                'id_karyawan' => $karyawanId,
                'nama' => $karyawanData->nama,
                'jumlah_absent' => $absentDays,
                'jumlah_hadir' => $presentDays,
            ];
        }

        return response()->json([
            "message" => "Berhasil mendapatkan presensi",
            "data" => $karyawanPresensi,
        ], 200);
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
    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_karyawan' => 'required',
            'tanggal' => 'required|date_format:Y-m-d',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $karyawan = Karyawan::find($storeData['id_karyawan']);

        if (is_null($karyawan)) return response()->json([
            "message" => "Karyawan tidak ditemukan",
            "data" => null,
        ], 400);

        $presensi = PresensiAbsen::create(['id_karyawan' => $storeData['id_karyawan'], 'tanggal' => $storeData['tanggal'],]);

        return response()->json([
            "message" => "Berhasil menambahkan presensi",
            "data" => $presensi,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id_karyawan)
    {
        $month = $request->query('month');

        // Check if month is set in parameter 
        $currentMonth = isset($month) ? Carbon::now()->month($month) : Carbon::now();

        $absentRecords = PresensiAbsen::whereMonth('created_at', $currentMonth)
            ->with('id_karyawan')
            ->where('id_karyawan', $id_karyawan)
            ->get()
            ->groupBy('id_karyawan');

        $daysInCurrentMonth = $currentMonth->daysInMonth;

        $karyawan = Karyawan::find($id_karyawan);

        if (is_null($karyawan)) return response()->json([
            "message" => "Karyawan tidak ditemukan",
            "data" => null,
        ], 400);

        $karyawanId = $karyawan->id_karyawan;
        $absentDays = isset($absentRecords[$karyawanId]) ? $absentRecords[$karyawanId]->count() : 0;
        $presentDays = $daysInCurrentMonth - $absentDays;

        $response = [
            "id_karyawan" => $karyawanId,
            "nama" => $karyawan->nama,
            'jumlah_absent' => $absentDays,
            'jumlah_hadir' => $presentDays,
        ];
        return response()->json([
            "message" => "Berhasil mendapatkan presensi",
            "data" => $response,
        ], 200);
    }

    public function showByDate(Request $request)
    {
        $dateQuery = $request->query('date');

        if ($dateQuery) {
            $date = Carbon::createFromFormat('Y-m-d', $dateQuery);

            if (!$date || !$date->isValid()) {
                return response()->json([
                    "message" => "Format tanggal invalid.",
                    "data" => null,
                ], 400);
            }
        } else {
            $date = Carbon::now();
        }

        $presensi = [];

        $karyawan = Karyawan::all();

        foreach ($karyawan as $karyawanData) {
            $statusPresensi = PresensiAbsen::where('id_karyawan', $karyawanData->id_karyawan)
                ->whereDate('tanggal', $date)
                ->exists();

            $presensi[] = [
                'id_karyawan' => $karyawanData->id_karyawan,
                'nama' => $karyawanData->nama,
                'presensi' => $statusPresensi,
            ];
        }

        return response()->json([
            "message" => "Presensi karyawan pada tanggal $date",
            "data" => $presensi,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PresensiAbsen $presensiAbsen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePresensiAbsenRequest $request, PresensiAbsen $presensiAbsen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_presensi)
    {
        $presensi = PresensiAbsen::find($id_presensi);

        if (is_null($presensi)) {
            return response()->json([
                "message" => "Data presensi absen tidak ditemukan",
                "data" => null,
            ], 400);
        }

        $presensi->delete();

        return response()->json([
            "message" => "Berhasil menghapus presensi karyawan",
            "data" => $presensi,
        ], 200);
    }
}
