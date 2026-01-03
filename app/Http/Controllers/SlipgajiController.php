<?php

namespace App\Http\Controllers;

use App\Models\Slipgaji;
use App\Models\User;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendSlipGajiEmailJob;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\SlipGajiMail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;

class SlipgajiController extends Controller
{
    // ===============================
    // LIST SLIP GAJI
    // ===============================
    public function index()
    {
        $user = auth()->user();
        $data['start_year'] = config('global.start_year');

        if ($user->hasRole('karyawan')) {
            $data['slipgaji'] = Slipgaji::where('status', 1)
                ->orderBy('tahun')
                ->orderBy('bulan')
                ->get();

            return view('payroll.slipgaji.index_mobile', $data);
        }

        $data['slipgaji'] = Slipgaji::orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        return view('payroll.slipgaji.index', $data);
    }

    // ===============================
    // CREATE
    // ===============================
    public function create()
    {
        return view('payroll.slipgaji.create', [
            'list_bulan' => config('global.list_bulan'),
            'start_year' => config('global.start_year'),
        ]);
    }

    // ===============================
    // STORE (HANYA SIMPAN DATA)
    // ===============================
    public function store(Request $request)
    {
        try {
            Slipgaji::firstOrCreate([
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
            ], [
                'kode_slip_gaji' => 'GJ' . $request->bulan . $request->tahun,
                'status' => $request->status
            ]);

            return Redirect::back()->with(messageSuccess('Slip gaji berhasil dibuat'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    // ===============================
    // EDIT
    // ===============================
    public function edit($kode_slip_gaji)
    {
        $kode_slip_gaji = Crypt::decrypt($kode_slip_gaji);

        return view('payroll.slipgaji.edit', [
            'slipgaji'   => Slipgaji::where('kode_slip_gaji', $kode_slip_gaji)->first(),
            'list_bulan' => config('global.list_bulan'),
            'start_year' => config('global.start_year'),
        ]);
    }

    // ===============================
    // UPDATE
    // ===============================
    public function update(Request $request, $kode_slip_gaji)
    {
        $kode_slip_gaji = Crypt::decrypt($kode_slip_gaji);

        try {
            Slipgaji::where('kode_slip_gaji', $kode_slip_gaji)->update([
                'bulan'  => $request->bulan,
                'tahun'  => $request->tahun,
                'status' => $request->status,
            ]);

            return Redirect::back()->with(messageSuccess('Data berhasil disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    // ===============================
    // DELETE
    // ===============================
    public function destroy($kode_slip_gaji)
    {
        $kode_slip_gaji = Crypt::decrypt($kode_slip_gaji);

        try {
            Slipgaji::where('kode_slip_gaji', $kode_slip_gaji)->delete();
            return Redirect::back()->with(messageSuccess('Data berhasil dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    // ======================================================
    // 🔥 PUBLISH + GENERATE PDF + KIRIM EMAIL
    // ======================================================
    public function publish($kode_slip_gaji)
    {
        $kode_slip_gaji = Crypt::decrypt($kode_slip_gaji);

        $slip = Slipgaji::where('kode_slip_gaji', $kode_slip_gaji)->firstOrFail();

        // Periode slip gaji
        $periode_dari   = date('Y-m-01', strtotime($slip->tahun . '-' . $slip->bulan . '-01'));
        $periode_sampai = date('Y-m-t', strtotime($periode_dari));

        // Ambil presensi karyawan
        $presensi = Presensi::with('karyawan')
            ->whereBetween('tanggal', [$periode_dari, $periode_sampai])
            ->get()
            ->groupBy('nik');

        // Default dummy data tambahan (sesuaikan dengan kebutuhan asli)
        $generalsetting = (object)[
            'nama_perusahaan' => 'PT. CRAZE INDONESIA',
            'total_jam_bulan' => 173
        ];
        $datalibur      = [];
        $datalembur     = [];
        $jenis_tunjangan = [];
        $denda_list     = [];

        foreach ($presensi as $nik => $rows) {

            $karyawan = $rows->first()->karyawan;

            if (!$karyawan || empty($karyawan->email)) {
                continue;
            }

            // 1️⃣ Generate PDF
            $pdf = Pdf::loadView('laporan.slip_cetak', [
                'laporan_presensi' => $rows->toArray(), // data presensi per karyawan
                'periode_dari'     => $periode_dari,
                'periode_sampai'   => $periode_sampai,
                'generalsetting'   => $generalsetting,
                'datalibur'        => $datalibur,
                'datalembur'       => $datalembur,
                'jenis_tunjangan'  => $jenis_tunjangan,
                'denda_list'       => $denda_list,
            ]);

            // 2️⃣ Simpan ke storage/public/slips
            $filename = "SlipGaji_{$nik}_{$slip->bulan}_{$slip->tahun}.pdf";
            Storage::put("public/slips/$filename", $pdf->output());

            // 3️⃣ Dispatch job dengan path file
            dispatch(new SendSlipGajiEmailJob(
                $karyawan->email,          // email penerima
                $karyawan->nama,           // nama
                "public/slips/$filename",  // path PDF
                $filename,                 // nama file
                $slip->bulan . ' ' . $slip->tahun // periode
            ));
        }

        // Update status publish
        $slip->update(['status' => 1]);

        return Redirect::back()->with(messageSuccess('Slip gaji berhasil dipublish & email terkirim'));
    }

    // ======================================================
    // CETAK PDF SLIP GAJI (TAMPILKAN LANGSUNG)
    // ======================================================
    public function cetakslipgaji($kode_slip_gaji)
    {
        $kode_slip_gaji = Crypt::decrypt($kode_slip_gaji);

        $slip = Slipgaji::where('kode_slip_gaji', $kode_slip_gaji)->firstOrFail();

        // Periode slip gaji
        $periode_dari   = date('Y-m-01', strtotime($slip->tahun . '-' . $slip->bulan . '-01'));
        $periode_sampai = date('Y-m-t', strtotime($periode_dari));

        // Ambil presensi karyawan
        $presensi = Presensi::with('karyawan')
            ->whereBetween('tanggal', [$periode_dari, $periode_sampai])
            ->get()
            ->groupBy('nik');

        // Default dummy data tambahan
        $generalsetting = (object)[
            'nama_perusahaan' => 'PT. CRAZE INDONESIA',
            'total_jam_bulan' => 173
        ];
        $datalibur      = [];
        $datalembur     = [];
        $jenis_tunjangan = [];
        $denda_list     = [];

        // Untuk demo, ambil karyawan pertama
        $rows = $presensi->first();
        if (!$rows) {
            return redirect()->back()->with(messageError('Tidak ada data presensi untuk slip ini.'));
        }

        $pdf = Pdf::loadView('laporan.slip_cetak', [
            'laporan_presensi' => $rows->toArray(),
            'periode_dari'     => $periode_dari,
            'periode_sampai'   => $periode_sampai,
            'generalsetting'   => $generalsetting,
            'datalibur'        => $datalibur,
            'datalembur'       => $datalembur,
            'jenis_tunjangan'  => $jenis_tunjangan,
            'denda_list'       => $denda_list,
        ]);

        // Tampilkan langsung di browser
        return $pdf->stream("SlipGaji_{$slip->kode_slip_gaji}.pdf");
    }
}
