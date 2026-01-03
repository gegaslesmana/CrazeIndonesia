@extends('layouts.mobile.app')
@section('content')
<style>
    body {
        font-family: 'Helvetica', 'Arial', sans-serif;
        margin: 0;
        padding: 15px;
        background: #f5f5f5;
        font-size: 12px;
        line-height: 1.4;
        color: #333;
    }

    .pdf-container {
        width: 100%;
        padding: 10px;
        box-sizing: border-box;
    }

    .slip {
        background: #fff;
        margin: auto;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        page-break-inside: avoid;
        max-width: 800px;
        margin-bottom: 20px;
    }

    /* Header */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        border-bottom: 2px solid #28a745;
        padding-bottom: 10px;
    }
    .header .logo {
        width: 80px;
    }
    .header .company-info {
        text-align: right;
    }
    .company-name {
        font-size: 18px;
        font-weight: bold;
        color: #28a745;
    }
    .company-address {
        font-size: 12px;
        color: #555;
    }

    /* Employee Info */
    .employee-info {
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
    }
    .employee-info .info-left, .employee-info .info-right {
        width: 48%;
    }
    .employee-info .label {
        font-weight: bold;
    }

    /* Section Titles */
    .section-title {
        font-size: 14px;
        font-weight: bold;
        color: #fff;
        background: #28a745;
        padding: 5px 10px;
        border-radius: 4px;
        margin: 15px 0 5px 0;
    }
    .section-body {
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 10px;
    }

    .row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 4px;
    }

    .currency {
        font-family: monospace;
    }

    .total {
        background: #f0f0f0;
        font-weight: bold;
        padding: 8px 10px;
        margin-top: 10px;
        border-radius: 4px;
        display: flex;
        justify-content: space-between;
    }

    .footer {
        text-align: center;
        font-size: 10px;
        color: #777;
        margin-top: 25px;
        border-top: 1px dashed #ccc;
        padding-top: 10px;
    }
</style>

<div class="pdf-container">
    @foreach ($laporan_presensi as $d)
        @php
            $total_denda = 0;
            $total_potongan_jam = 0;
            $total_tunjangan = 0;
            $total_jam_lembur = 0;

            foreach ($jenis_tunjangan as $j) {
                $total_tunjangan += $d[$j->kode_jenis_tunjangan] ?? 0;
            }

            $upah_perjam = $d['gaji_pokok'] / $generalsetting->total_jam_bulan;

            $tanggal_presensi = $periode_dari;
            while (strtotime($tanggal_presensi) <= strtotime($periode_sampai)) {
                $denda = 0;
                $potongan_jam = 0;
                $search = ['nik' => $d['nik'], 'tanggal' => $tanggal_presensi];
                $ceklibur = ceklibur($datalibur, $search);
                $ceklembur = ceklembur($datalembur, $search);
                $lembur = hitungLembur($ceklembur);
                $jml_jam_lembur = !empty($ceklembur) ? $lembur : 0;

                if(isset($d[$tanggal_presensi])) {
                    if($d[$tanggal_presensi]['status'] == 'h'){
                        $jam_masuk = $tanggal_presensi . ' ' . $d[$tanggal_presensi]['jam_masuk'];
                        $terlambat = hitungjamterlambat($d[$tanggal_presensi]['jam_in'], $jam_masuk);
                        if($terlambat != null){
                            if($terlambat['desimal_terlambat'] < 1){
                                $potongan_jam_terlambat = 0;
                                $denda = hitungdenda($denda_list, $terlambat['menitterlambat']);
                            } else {
                                $potongan_jam_terlambat = $terlambat['desimal_terlambat'];
                                $denda = 0;
                            }
                        }
                        $pulangcepat = hitungpulangcepat(
                            $tanggal_presensi,
                            $d[$tanggal_presensi]['jam_out'],
                            $d[$tanggal_presensi]['jam_pulang'],
                            $d[$tanggal_presensi]['istirahat'],
                            $d[$tanggal_presensi]['jam_awal_istirahat'],
                            $d[$tanggal_presensi]['jam_akhir_istirahat'],
                            $d[$tanggal_presensi]['lintashari']
                        );
                        $potongan_tidak_absen_masuk_atau_pulang = empty($d[$tanggal_presensi]['jam_out']) || empty($d[$tanggal_presensi]['jam_in']) ? $d[$tanggal_presensi]['total_jam'] : 0;
                        $potongan_jam = $potongan_tidak_absen_masuk_atau_pulang == 0 ? $pulangcepat + $potongan_jam_terlambat : $potongan_tidak_absen_masuk_atau_pulang;
                    } elseif($d[$tanggal_presensi]['status'] == 'i' || $d[$tanggal_presensi]['status'] == 'a'){
                        $potongan_jam = $d[$tanggal_presensi]['total_jam'];
                    }
                }

                $total_denda += $denda;
                $total_potongan_jam += $potongan_jam;
                $total_jam_lembur += $jml_jam_lembur;

                $tanggal_presensi = date('Y-m-d', strtotime('+1 day', strtotime($tanggal_presensi)));
            }

            $jumlah_potongan_jam = round($upah_perjam) * $total_potongan_jam;
            $total_potongan = round($jumlah_potongan_jam) + $total_denda + $d['bpjs_kesehatan'] + $d['bpjs_tenagakerja'];
            $gaji_bersih = $d['gaji_pokok'] + $total_tunjangan - $total_potongan + ($d['penambah'] ?? 0) - ($d['pengurang'] ?? 0) + round($total_jam_lembur * $upah_perjam);
        @endphp

        <div class="slip">
            <!-- Header -->
            <div class="header">
                @php
                    $logo_path = storage_path('app/public/logo/1765366745.png');
                    $logo_base64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logo_path));
                @endphp
                <img src="{{ $logo_base64 }}" alt="Logo PT.Craze Indonesia" class="logo">
                <div class="company-info">
                    <div class="company-name">PT. Craze Indonesia</div>
                    <div class="company-address">Pasirgombong, Cikarang Utara, Bekasi Regency, West Java 17530<br>Telp: (021) 89844532</div>
                </div>
            </div>

            <!-- Employee Info -->
            <div class="employee-info">
                <div class="info-left">
                    <div><span class="label">NIK:</span> {{ $d['nik_show'] ?? $d['nik'] }}</div>
                    <div><span class="label">Nama:</span> {{ $d['nama_karyawan'] }}</div>
                    <div><span class="label">Jabatan:</span> {{ $d['nama_jabatan'] }}</div>
                </div>
                <div class="info-right">
                    <div><span class="label">Dept:</span> {{ $d['nama_dept'] }}</div>
                    <div><span class="label">Cabang:</span> {{ $d['kode_cabang'] }}</div>
                    <div><span class="label">Periode:</span> {{ date('d/m/Y', strtotime($periode_dari)) }} - {{ date('d/m/Y', strtotime($periode_sampai)) }}</div>
                </div>
            </div>

            <!-- Penghasilan -->
            <div class="section-title">PENGHASILAN</div>
            <div class="section-body">
                <div class="row"><div>Gaji Pokok</div><div class="currency">{{ number_format($d['gaji_pokok'],0,',','.') }}</div></div>
                @foreach ($jenis_tunjangan as $j)
                    @if (($d[$j->kode_jenis_tunjangan] ?? 0) > 0)
                        <div class="row">
                            <div>{{ $j->jenis_tunjangan }}</div>
                            <div class="currency">{{ number_format($d[$j->kode_jenis_tunjangan],0,',','.') }}</div>
                        </div>
                    @endif
                @endforeach
                @if($total_jam_lembur>0)
                    <div class="row">
                        <div>Lembur ({{ number_format($total_jam_lembur,2) }} jam)</div>
                        <div class="currency">{{ number_format(round($total_jam_lembur*$upah_perjam),0,',','.') }}</div>
                    </div>
                @endif
                <div class="total">Sub Total Penghasilan
                    <span>{{ number_format($d['gaji_pokok'] + $total_tunjangan + round($total_jam_lembur*$upah_perjam),0,',','.') }}</span>
                </div>
            </div>

            <!-- Potongan -->
            <div class="section-title">POTONGAN</div>
            <div class="section-body">
                @if($total_denda>0)
                    <div class="row"><div>Denda</div><div class="currency">{{ number_format($total_denda,0,',','.') }}</div></div>
                @endif
                @if($jumlah_potongan_jam>0)
                    <div class="row"><div>Pot. Jam ({{ number_format($total_potongan_jam,2) }})</div><div class="currency">{{ number_format($jumlah_potongan_jam,0,',','.') }}</div></div>
                @endif
                @if($d['bpjs_kesehatan']>0)
                    <div class="row"><div>BPJS Kes</div><div class="currency">{{ number_format($d['bpjs_kesehatan'],0,',','.') }}</div></div>
                @endif
                @if($d['bpjs_tenagakerja']>0)
                    <div class="row"><div>BPJS TK</div><div class="currency">{{ number_format($d['bpjs_tenagakerja'],0,',','.') }}</div></div>
                @endif
                <div class="total">Total Potongan
                    <span>{{ number_format($total_potongan,0,',','.') }}</span>
                </div>
            </div>

            <!-- Gaji Bersih -->
            <div class="section-title" style="background:#17a2b8;">GAJI BERSIH</div>
            <div class="total">
                <span>GAJI BERSIH</span>
                <span>{{ number_format($gaji_bersih,0,',','.') }}</span>
            </div>

            <div class="footer">
                Dicetak: {{ date('d/m/Y H:i') }} | Sistem Payroll v1.0
            </div>
        </div>
    @endforeach
</div>
@endsection
