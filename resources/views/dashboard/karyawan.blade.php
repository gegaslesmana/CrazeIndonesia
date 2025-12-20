@extends('layouts.mobile.app')
@section('content')
    <style>
        :root {
            --bg-body: #dff9fb;
            --bg-nav: #ffffff;
            --color-nav: #32745e;
            --color-nav-active: #58907D;
            --bg-indicator: #32745e;
            --color-nav-hover: #3ab58c;
        }


        #header-section {
            height: auto;
            padding: 20px;
            position: relative;

        }

        #section-logout {
            position: absolute;
            right: 15px;
            top: 15px;
        }

        .logout-btn {
            color: var(--bg-indicator);
            font-size: 30px;
            text-decoration: none;
        }

        .logout-btn:hover {
            color: var(--color-nav-hover);

        }



        #section-user {
            margin-top: 50px;
            display: flex;
            justify-content: space-between
        }

        #user-info {
            margin-left: 0px !important;
            line-height: 2px;
        }

        #user-info h3 {
            color: var(--bg-indicator);
        }

        #user-info span {
            color: var(--color-nav);
        }

        #section-presensi {
            margin-top: 15px;
        }

        #presensi-today {
            display: flex;
            justify-content: space-between
        }

        #presensi-today h4 {
            color: #32745e;
            font-weight: bold;
            margin: 0
        }

        #presensi-today #presensi-text {
            color: #12855f;
        }

        #presensi-data {
            display: flex;
            justify-content: space-around
        }

        #presensi-icon {
            font-size: 30px;
            margin-right: 10px;
        }


        #rekap-section {

            margin-top: 50px;
            padding: 20px;
            position: relative;
        }

        #rekap-section #title {
            color: var(--bg-indicator);
        }

        #histori-section {
            padding: 0px 20px;
            position: relative;
        }

        #app-section {


            padding: 20px;

        }

        #app-section #title {
            color: var(--bg-indicator);
        }

        .iconpresence {
            color: #32745e
        }

        #jam {
            color: var(--bg-indicator);
            font-weight: bold;
            font-size: 48px;

        }
    </style>
    <div id="header-section">
        <div id="section-logout">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="#" class="logout-btn"
                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                    <ion-icon name="exit-outline"></ion-icon>
                </a>
            </form>
        </div>
        <div id="section-user">
            <div id="user-info">
                <h3 id="user-name">{{ $karyawan->nama_karyawan }}👋</h3>
                <span id="user-role">{{ $karyawan->nama_jabatan }}</span>
                <span id="user-role">({{ $karyawan->nama_dept }})</span>

            </div>
            <a href="{{ route('profile.index') }}">
                @if (!empty($karyawan->foto))
                    @if (Storage::disk('public')->exists('/karyawan/' . $karyawan->foto))
                        <div
                            style="width: 80px; height: 80px; background-image: url({{ getfotoKaryawan($karyawan->foto) }}); background-size: cover; background-position: center; border-radius: 50%;">


                        </div>
                    @else
                        <div class="avatar avatar-xs me-2">
                            <img src="{{ asset('assets/template/img/sample/avatar/avatar1.jpg') }}" alt="avatar" class="imaged w64 rounded">
                        </div>
                    @endif
                @else
                    <div class="avatar avatar-xs me-2">
                        <img src="{{ asset('assets/template/img/sample/avatar/avatar1.jpg') }}" alt="avatar" class="imaged w64 rounded">
                    </div>
                @endif
            </a>
        </div>
        <div id="section-jam " class="text-center mt-1 mb-2">
            <h2 id="jam" class="mb-2" style="text-shadow: 0px 0px 2px #04ab86b7; line-height: 1rem"></h2>
            <span class="">Hari ini : {{ getNamaHari(date('D')) }}, {{ DateToIndo(date('Y-m-d')) }}</span>
        </div>
        <div id="section-presensi">
            <div class="card">
                <div class="card-body" id="presensi-today">
                    <div id="presensi-data">
                        <div id="presensi-icon">
                            @php
                                $jam_in = $presensi && $presensi->jam_in != null ? $presensi->jam_in : null;
                            @endphp
                            @if ($presensi && $presensi->foto_in != null)
                                @php
                                    $path = Storage::url('uploads/absensi/' . $presensi->foto_in . '?v=' . time());
                                @endphp
                                <img src="{{ url($path) }}" alt="" class="imaged w48">
                            @else
                                <ion-icon name="camera"></ion-icon>
                            @endif
                        </div>
                        <div id="presensi-detail">
                            <h4>Jam Masuk</h4>
                            <span class="presensi-text">
                                @if ($jam_in != null)
                                    {{ date('H:i', strtotime($jam_in)) }}
                                @else
                                    <ion-icon name="hourglass-outline"></ion-icon> Belum Absen
                                @endif
                            </span>
                        </div>

                    </div>
                    <div class="outer">
                        <div class="inner"></div>
                    </div>
                    <div id="presensi-data">
                        <div id="presensi-icon">
                            @php
                                $jam_out = $presensi && $presensi->jam_out != null ? $presensi->jam_out : null;
                            @endphp
                            @if ($presensi && $presensi->foto_out != null)
                                @php
                                    $path = Storage::url('uploads/absensi/' . $presensi->foto_out . '?v=' . time());
                                @endphp
                                <img src="{{ url($path) }}" alt="" class="imaged w48">
                            @else
                                <ion-icon name="camera"></ion-icon>
                            @endif
                        </div>
                        <div id="presensi-detail">
                            <h4>Jam Pulang</h4>
                            <span class="presensi-text">
                                @if ($jam_out != null)
                                    {{ date('H:i', strtotime($jam_out)) }}
                                @else
                                    <i class="ti ti-hourglass-low text-warning"></i> Belum Absen
                                @endif
                            </span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="app-section">
        <div class="row">
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 5px 5px !important; line-height:0.8rem">
                        <img src="{{ asset('assets/template/img/3d/hadir.webp') }}" alt="" style="width: 50px" class="mb-1">
                        <br>
                        <span style="font-size: 0.8rem; font-weight:500">
                            Hadir
                        </span>
                        <span class="badge bg-success" style="position: absolute; top: 5px; right: 5px">
                            {{ $rekappresensi ? $rekappresensi->hadir : 0 }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 5px 5px !important; line-height:0.8rem">
                        <img src="{{ asset('assets/template/img/3d/sakit.png') }}" alt="" style="width: 50px" class="mb-1">
                        <br>
                        <span style="font-size: 0.8rem; font-weight:500">Sakit</span>
                        <span class="badge bg-success" style="position: absolute; top: 5px; right: 5px">
                            {{ $rekappresensi ? $rekappresensi->sakit : 0 }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 5px 5px !important; line-height:0.8rem">
                        <img src="{{ asset('assets/template/img/3d/izin.webp') }}" alt="" style="width: 50px" class="mb-1">
                        <br>
                        <span style="font-size: 0.8rem; font-weight:500">Izin</span>
                        <span class="badge bg-success" style="position: absolute; top: 5px; right: 5px">
                            {{ $rekappresensi ? $rekappresensi->izin : 0 }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 5px 5px !important; line-height:0.8rem">
                        <img src="{{ asset('assets/template/img/3d/cuti.png') }}" alt="" style="width: 50px" class="mb-1">
                        <br>
                        <span style="font-size: 0.8rem; font-weight:500">Cuti</span>
                        <span class="badge bg-success" style="position: absolute; top: 5px; right: 5px">
                            {{ $rekappresensi ? $rekappresensi->cuti : 0 }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-3">
                <a href="{{ route('karyawan.idcard', Crypt::encrypt($karyawan->nik)) }}">
                    <div class="card">
                        <div class="card-body text-center" style="padding: 5px 5px !important; line-height:0.8rem">
                            <img src="{{ asset('assets/template/img/3d/card.webp') }}" alt="" style="width: 50px" class="mb-0">
                            <br>
                            <span style="font-size: 0.8rem; font-weight:500" class="mb-2">
                                ID Card
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-3">
                <a href="{{ route('presensiistirahat.create') }}">
                    <div class="card">
                        <div class="card-body text-center" style="padding: 5px 5px !important; line-height:0.8rem">
                            <img src="{{ asset('assets/template/img/3d/alarm.png') }}" alt="" style="width: 50px" class="mb-0">
                            <br>
                            <span style="font-size: 0.8rem; font-weight:500" class="mb-2">
                                Istirahat
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-3">
                <a href="{{ route('lembur.index') }}">
                    <div class="card">
                        <div class="card-body text-center" style="padding: 5px 5px !important; line-height:0.8rem">
                            <img src="{{ asset('assets/template/img/3d/clock.png') }}" alt="" style="width: 50px" class="mb-0">
                            <br>
                            <span style="font-size: 0.8rem; font-weight:500" class="mb-2">
                                Lembur
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-3">
                <a href="{{ route('slipgaji.index') }}">
                    <div class="card">
                        <div class="card-body text-center" style="padding: 5px 5px !important; line-height:0.8rem">
                            <img src="{{ asset('assets/template/img/3d/slipgaji.png') }}" alt="" style="width: 50px" class="mb-0">
                            <br>
                            <span style="font-size: 0.8rem; font-weight:500" class="mb-2">
                                Slip Gaji
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row mt-2">
            @can('aktivitaskaryawan.index')
                <div class="col-3">
                    <a href="{{ route('aktivitaskaryawan.index') }}">
                        <div class="card">
                            <div class="card-body text-center" style="padding: 5px 5px !important; line-height:0.8rem">
                                <img src="{{ asset('assets/template/img/3d/activity.png') }}" alt="" style="width: 50px" class="mb-0">
                                <br>
                                <span style="font-size: 0.8rem; font-weight:500" class="mb-2">
                                    Aktivitas
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            @endcan
            @can('kunjungan.index')
                <div class="col-3">
                    <a href="{{ route('kunjungan.index') }}">
                        <div class="card">
                            <div class="card-body text-center" style="padding: 5px 5px !important; line-height:0.8rem">
                                <img src="{{ asset('assets/template/img/3d/maps.png') }}" alt="" style="width: 50px" class="mb-0">
                                <br>
                                <span style="font-size: 0.8rem; font-weight:500" class="mb-2">
                                    Visit
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            @endcan
            <div class="col-3">
                <a href="javascript:void(0)" id="btnDaftarkanWajah">
                    <div class="card">
                        <div class="card-body text-center" style="padding: 5px 5px !important; line-height:0.8rem">
                            <img src="{{ asset('assets/template/img/3d/scanwajah.png') }}" alt="" style="width: 50px" class="mb-0">
                            <br>
                            <span style="font-size: 0.8rem; font-weight:500" class="mb-2">
                                Wajah
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div id="histori-section">
        <div class="tab-pane fade show active" id="pilled" role="tabpanel">
            <ul class="nav nav-tabs style1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#historipresensi" role="tab">
                        30 Hari terakhir
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#lembur" role="tab">
                        Lembur <span class="badge badge-danger ml-1">{{ $notiflembur }}</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="tab-content mt-2" style="margin-bottom:100px;">
            <div class="tab-pane fade show active" id="historipresensi" role="tabpanel">
                <div class="row mb-1">
                    <div class="col">
                        {{-- {{ $d->jam_out != null ? 'historibordergreen' : 'historiborderred' }} --}}
                        @foreach ($datapresensi as $d)
                            @if ($d->status == 'h')
                                @php
                                    $jam_in = date('Y-m-d H:i', strtotime($d->jam_in));
                                    $jam_masuk = date('Y-m-d H:i', strtotime($d->tanggal . ' ' . $d->jam_masuk));
                                @endphp
                                <div class="card historicard historibordergreen mb-1">
                                    <div class="historicontent">
                                        <div class="historidetail1">
                                            <div class="iconpresence">
                                                <ion-icon name="finger-print-outline" style="font-size: 48px"></ion-icon>
                                            </div>
                                            <div class="datepresence">
                                                <h4>{{ DateToIndo($d->tanggal) }}</h4>
                                                <span class="timepresence">
                                                    @if ($d->jam_in != null)
                                                        {{ date('H:i', strtotime($d->jam_in)) }}
                                                    @else
                                                        <span class="text-danger">
                                                            <ion-icon name="hourglass-outline"></ion-icon> Belum Absen
                                                        </span>
                                                    @endif
                                                    -
                                                    @if ($d->jam_out != null)
                                                        {{ date('H:i', strtotime($d->jam_out)) }}
                                                    @else
                                                        <span class="text-danger">
                                                            <ion-icon name="hourglass-outline"></ion-icon> Belum Absen
                                                        </span>
                                                    @endif
                                                </span>

                                                @if ($d->istirahat_in != null)
                                                    <br>
                                                    <span class="timepresence text-info">
                                                        {{ date('H:i', strtotime($d->istirahat_in)) }} -
                                                        @if ($d->istirahat_out != null)
                                                            {{ date('H:i', strtotime($d->istirahat_out)) }}
                                                        @else
                                                            <ion-icon name="hourglass-outline"></ion-icon>
                                                        @endif
                                                    </span>
                                                @endif
                                                <br>
                                                @if ($d->jam_in != null)
                                                    @php
                                                        $terlambat = hitungjamterlambat(
                                                            date('H:i', strtotime($jam_in)),
                                                            date('H:i', strtotime($jam_masuk)),
                                                        );

                                                    @endphp
                                                    {!! $terlambat['show'] !!}
                                                @endif


                                            </div>
                                        </div>
                                        <div class="historidetail2">
                                            <h4>{{ $d->nama_jam_kerja }}</h4>
                                            <span class="timepresence">
                                                {{ date('H:i', strtotime($d->jam_masuk)) }} -
                                                {{ date('H:i', strtotime($d->jam_pulang)) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @elseif($d->status == 'i')
                                <div class="card historicard historibordergreen mb-1">
                                    <div class="historicontent">
                                        <div class="historidetail1">
                                            <div class="iconpresence">
                                                <ion-icon name="document-text-outline" style="font-size: 48px; color: #1f7ee4"></ion-icon>
                                            </div>
                                            <div class="datepresence">
                                                <h4>{{ DateToIndo($d->tanggal) }}</h4>
                                                <h4 class="timepresence">
                                                    Izin Absen
                                                </h4>
                                                <span>{{ $d->keterangan_izin }}</span>
                                            </div>
                                        </div>
                                        <div class="historidetail2">
                                            <h4>{{ $d->nama_jam_kerja }}</h4>
                                            <span class="timepresence">
                                                {{ date('H:i', strtotime($d->jam_masuk)) }} -
                                                {{ date('H:i', strtotime($d->jam_pulang)) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @elseif($d->status == 'i')
                                <div class="card historicard historibordergreen mb-1">
                                    <div class="historicontent">
                                        <div class="historidetail1">
                                            <div class="iconpresence">
                                                <ion-icon name="document-text-outline" style="font-size: 48px; color: #1f7ee4"></ion-icon>
                                            </div>
                                            <div class="datepresence">
                                                <h4>{{ DateToIndo($d->tanggal) }}</h4>
                                                <h4 class="timepresence">
                                                    Izin Cuti
                                                </h4>
                                                <span>{{ $d->keterangan_cuti }}</span>
                                            </div>
                                        </div>
                                        <div class="historidetail2">
                                            <h4>{{ $d->nama_jam_kerja }}</h4>
                                            <span class="timepresence">
                                                {{ date('H:i', strtotime($d->jam_masuk)) }} -
                                                {{ date('H:i', strtotime($d->jam_pulang)) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @elseif($d->status == 's')
                                <div class="card historicard historibordergreen mb-1">
                                    <div class="historicontent">
                                        <div class="historidetail1">
                                            <div class="iconpresence">
                                                <ion-icon name="bag-add-outline" style="font-size: 48px; color: #d4095a"></ion-icon>
                                            </div>
                                            <div class="datepresence">
                                                <h4>{{ DateToIndo($d->tanggal) }}</h4>
                                                <h4 class="timepresence">
                                                    Izin Sakit
                                                </h4>
                                                <span>{{ $d->keterangan_sakit }}</span>
                                            </div>
                                        </div>
                                        <div class="historidetail2">
                                            <h4>{{ $d->nama_jam_kerja }}</h4>
                                            <span class="timepresence">
                                                {{ date('H:i', strtotime($d->jam_masuk)) }} -
                                                {{ date('H:i', strtotime($d->jam_pulang)) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="lembur" role="tabpanel">
                @foreach ($lembur as $d)
                    <a href="{{ route('lembur.createpresensi', Crypt::encrypt($d->id)) }}">
                        <div class="card historicard historibordergreen mb-1">
                            <div class="historicontent">
                                <div class="historidetail1">
                                    <div class="iconpresence">
                                        <ion-icon name="timer-outline" style="font-size: 48px; color: #1f7ee4"></ion-icon>
                                    </div>
                                    <div class="datepresence">
                                        <h4>{{ DateToIndo($d->tanggal) }}</h4>
                                        <h4 class="timepresence">
                                            Lembur
                                        </h4>

                                        <p>{{ $d->keterangan }}</p>
                                        @if ($d->lembur_in != null)
                                            <span class="badge badge-success">
                                                <ion-icon name="timer-outline"></ion-icon>
                                                {{ date('H:i', strtotime($d->lembur_in)) }}
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                <ion-icon name="timer-outline"></ion-icon>
                                                Belum Absen
                                            </span>
                                        @endif
                                        -
                                        @if ($d->lembur_out != null)
                                            <span class="badge badge-success">
                                                <ion-icon name="timer-outline"></ion-icon>
                                                {{ date('H:i', strtotime($d->lembur_out)) }}
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                <ion-icon name="timer-outline"></ion-icon>
                                                Belum Absen
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="historidetail2">
                                    {{-- <h4>{{ $d->nama_jam_kerja }}</h4>

                                    {{ date('H:i', strtotime($d->jam_masuk)) }} -
                                    {{ date('H:i', strtotime($d->jam_pulang)) }}
                                </span> --}}
                                    <span class="timepresence">
                                        {{ date('H:i', strtotime($d->lembur_mulai)) }} -
                                        {{ date('H:i', strtotime($d->lembur_selesai)) }}
                                        @if (date('Y-m-d', strtotime($d->lembur_selesai)) > date('Y-m-d', strtotime($d->lembur_mulai)))
                                            <ion-icon name="caret-up-outline" style="color: green"></ion-icon>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal Ucapan Ulang Tahun -->
    @if (isset($is_birthday) && $is_birthday)
        <!-- Custom Overlay Backdrop -->
        <div class="birthday-overlay" id="birthdayOverlay"></div>

        <!-- Confetti Container -->
        <div id="confetti-container"></div>

        <div class="modal fade" id="birthdayModal" tabindex="-1" role="dialog" aria-labelledby="birthdayModalLabel" aria-hidden="true"
            data-bs-backdrop="false" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content birthday-modal-content">
                    <!-- Close Button -->
                    <button type="button" class="birthday-close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <ion-icon name="close-circle-outline"></ion-icon>
                    </button>

                    <div class="modal-body birthday-modal-body">
                        <!-- Icons Section -->
                        <div class="birthday-icons">
                            <span style="font-size: 70px; filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.3)); animation: bounce 2s infinite;">🎂</span>
                            {{-- <ion-icon name="balloon-outline" class="birthday-icon birthday-icon-balloon"></ion-icon> --}}
                        </div>

                        <!-- Title Section -->
                        <div class="birthday-title-section">

                            <h2 class="birthday-title">Selamat Ulang Tahun!</h2>
                            <h3 class="birthday-name">{{ $karyawan->nama_karyawan }}</h3>
                            @if ($umur)
                                <p class="birthday-age">Selamat ulang tahun yang ke-<strong>{{ $umur }}</strong> tahun! 🎊</p>
                            @endif
                        </div>

                        <!-- Wishes Section -->
                        <div class="birthday-wishes">
                            <div class="birthday-wish-item">
                                <ion-icon name="star" class="wish-icon"></ion-icon>
                                <span>Panjang umur & sehat selalu</span>
                            </div>
                            <div class="birthday-wish-item">
                                <ion-icon name="star" class="wish-icon"></ion-icon>
                                <span>Bahagia selalu dalam pekerjaan</span>
                            </div>
                            <div class="birthday-wish-item">
                                <ion-icon name="star" class="wish-icon"></ion-icon>
                                <span>Sukses dalam karir</span>
                            </div>
                            <div class="birthday-wish-item">
                                <ion-icon name="star" class="wish-icon"></ion-icon>
                                <span>Diberkahi rezeki yang berlimpah</span>
                            </div>
                        </div>

                        <!-- Button Section -->
                        <button type="button" class="btn btn-light btn-lg birthday-button" data-bs-dismiss="modal">
                            Terima Kasih! 🙏
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        /* Confetti Styles */
        #confetti-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1060;
            overflow: hidden;
        }

        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: #ffd700;
            position: absolute;
            animation: confetti-fall linear forwards;
        }

        .confetti:nth-child(1n) {
            background: #ffd700;
        }

        .confetti:nth-child(2n) {
            background: #ff6b6b;
        }

        .confetti:nth-child(3n) {
            background: #4ecdc4;
        }

        .confetti:nth-child(4n) {
            background: #95e1d3;
        }

        .confetti:nth-child(5n) {
            background: #ffe66d;
        }

        @keyframes confetti-fall {
            0% {
                transform: translateY(-100vh) rotate(0deg);
                opacity: 1;
            }

            100% {
                transform: translateY(100vh) rotate(720deg);
                opacity: 0;
            }
        }

        /* Birthday Modal Styles */
        .birthday-modal-content {
            border-radius: 20px !important;
            border: none !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3) !important;
            overflow: hidden;
        }

        .birthday-modal-body {
            padding: 40px 30px !important;
            background: linear-gradient(135deg, #32745e 0%, #58907D 100%) !important;
            border-radius: 20px !important;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* Close Button */
        .birthday-close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .birthday-close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        .birthday-close-btn ion-icon {
            font-size: 28px;
            color: #fff;
            filter: drop-shadow(1px 1px 2px rgba(0, 0, 0, 0.3));
        }

        /* Icons Section */
        .birthday-icons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin: 0 auto 25px auto;
            width: 100%;
            text-align: center;
            padding: 0;
            position: relative;
        }

        .birthday-icon {
            font-size: 70px;
            filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.3));
            animation: bounce 2s infinite;
            flex-shrink: 0;
            display: block;
            margin: 0;
            line-height: 1;
        }

        .birthday-icon-cake {
            color: #fff;
        }

        .birthday-icon-balloon {
            font-size: 70px;
            color: #ff6b6b;
            animation-delay: 0.2s;
        }

        /* Pastikan icons benar-benar centered */
        @media (max-width: 575px) {
            .birthday-icons {
                justify-content: center;
                align-items: center;
                gap: 15px;
                padding: 0;
                margin: 0 auto 20px auto;
                width: 100%;
            }

            .birthday-icon {
                font-size: 60px;
                margin: 0;
            }
        }

        /* Title Section */
        .birthday-title-section {
            margin-bottom: 25px;
        }

        .birthday-title {
            color: #fff;
            font-weight: bold;
            font-size: 28px;
            margin-bottom: 12px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .birthday-name {
            color: #fff;
            font-weight: 600;
            font-size: 22px;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .birthday-age {
            color: #fff;
            font-size: 18px;
            margin-bottom: 0;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        /* Wishes Section */
        .birthday-wishes {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            backdrop-filter: blur(10px);
        }

        .birthday-wish-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            font-size: 16px;
            margin: 8px 0;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            justify-content: flex-start;
        }

        .wish-icon {
            font-size: 18px;
            color: #ffd700;
            flex-shrink: 0;
        }

        /* Button Section */
        .birthday-button {
            border-radius: 25px !important;
            padding: 12px 40px !important;
            font-weight: 600 !important;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2) !important;
        }

        /* Bounce Animation */
        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        /* Modal Dialog */
        #birthdayModal .modal-dialog {
            max-width: 90%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            min-height: 100%;
            padding: 15px;
        }

        /* Memastikan modal benar-benar centered di mobile */
        @media (max-width: 575px) {
            #birthdayModal .modal-dialog {
                max-width: 90%;
                margin: 0 auto;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: calc(100vh - 30px);
                padding: 15px;
                position: relative;
            }

            #birthdayModal.modal.show .modal-dialog {
                transform: translateY(0);
                margin: auto;
            }

            .birthday-modal-body {
                padding: 30px 20px !important;
            }
        }

        @media (min-width: 576px) {
            #birthdayModal .modal-dialog {
                max-width: 500px;
                margin: 0 auto;
                display: flex;
                align-items: center;
                min-height: calc(100vh - 60px);
                padding: 30px;
            }

            .birthday-title {
                font-size: 32px;
            }

            .birthday-name {
                font-size: 24px;
            }
        }

        /* Custom Overlay Backdrop untuk modal ulang tahun */
        .birthday-overlay {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100% !important;
            height: 100% !important;
            min-height: 100vh !important;
            min-height: -webkit-fill-available !important;
            z-index: 1040 !important;
            background-color: rgba(0, 0, 0, 0.6) !important;
            margin: 0 !important;
            padding: 0 !important;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            cursor: pointer;
        }

        .birthday-overlay.show {
            opacity: 1 !important;
            visibility: visible !important;
        }

        /* Pastikan modal muncul DI ATAS overlay */
        #birthdayModal {
            z-index: 1050 !important;
            position: fixed !important;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            margin: 0;
        }

        #birthdayModal.show {
            z-index: 1050 !important;
            display: flex !important;
        }

        #birthdayModal .modal-dialog {
            z-index: 1051 !important;
            position: relative !important;
            margin: auto !important;
        }

        #birthdayModal .modal-content {
            z-index: 1052 !important;
            position: relative !important;
        }

        /* Pastikan backdrop menutupi semua elemen termasuk status bar dan bottom nav */
        body.modal-open {
            overflow: hidden !important;
            padding-right: 0 !important;
        }

        body.modal-open .appHeader,
        body.modal-open .bottomMenu,
        body.modal-open #appCapsule {
            position: relative;
            z-index: 1 !important;
        }

        /* Untuk mobile, pastikan overlay menutupi seluruh viewport termasuk safe area */
        @media (max-width: 768px) {
            .birthday-overlay {
                height: 100vh !important;
                height: -webkit-fill-available !important;
                min-height: 100vh !important;
                min-height: -webkit-fill-available !important;
            }

            /* Pastikan modal tetap di atas overlay */
            #birthdayModal {
                z-index: 1050 !important;
                position: fixed !important;
            }

            #birthdayModal.show {
                z-index: 1050 !important;
            }

            #birthdayModal .modal-dialog {
                z-index: 1051 !important;
                position: relative !important;
            }

            #birthdayModal .modal-content {
                z-index: 1052 !important;
                position: relative !important;
            }
        }
    </style>
@endsection
@push('myscript')
    <!-- Preload Face Recognition Models dengan IndexedDB Caching -->
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <script src="{{ asset('js/face-model-cache.js') }}"></script>
    <script>
        // Auto preload models dan descriptors di background saat dashboard load (non-blocking)
        (function() {
            function startPreload() {
                if (typeof faceapi !== 'undefined' && window.FaceModelCache) {
                    // Delay 1 detik agar tidak blocking render halaman
                    setTimeout(async () => {
                        // Preload models dulu
                        await window.FaceModelCache.preloadFaceModels();
                        console.log('[Dashboard] Face models preloaded in background');

                        // Setelah models loaded, preload descriptors
                        @if (isset($karyawan) && $karyawan)
                            const nik = "{{ $karyawan->nik }}";
                            const label = "{{ $karyawan->nik }}-{{ getNamaDepan(strtolower($karyawan->nama_karyawan)) }}";

                            // Preload descriptors di background (non-blocking, delay 2 detik setelah models loaded)
                            setTimeout(() => {
                                window.FaceModelCache.preloadFaceDescriptors(nik, label).then((success) => {
                                    if (success) {
                                        console.log('[Dashboard] Face descriptors preloaded in background');
                                    }
                                }).catch(err => {
                                    console.warn('[Dashboard] Failed to preload descriptors:', err);
                                });
                            }, 2000);
                        @endif
                    }, 1000);
                } else {
                    // Retry jika face-api.js belum loaded
                    setTimeout(startPreload, 500);
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', startPreload);
            } else {
                startPreload();
            }
        })();
    </script>

    <script type="text/javascript">
        window.onload = function() {
            jam();
        }

        function jam() {
            var e = document.getElementById('jam'),
                d = new Date(),
                h, m, s;
            h = d.getHours();
            m = set(d.getMinutes());
            s = set(d.getSeconds());

            e.innerHTML = h + ':' + m + ':' + s;

            setTimeout('jam()', 1000);
        }

        function set(e) {
            e = e < 10 ? '0' + e : e;
            return e;
        }

        // Tampilkan modal ulang tahun jika ada
        @if (isset($is_birthday) && $is_birthday)
            $(document).ready(function() {
                // Fungsi untuk membuat confetti
                function createConfetti() {
                    var container = $('#confetti-container');
                    if (container.length === 0) return;

                    var colors = ['#ffd700', '#ff6b6b', '#4ecdc4', '#95e1d3', '#ffe66d'];
                    var confettiCount = 100;

                    for (var i = 0; i < confettiCount; i++) {
                        var confetti = $('<div class="confetti"></div>');
                        var left = Math.random() * 100;
                        var delay = Math.random() * 3;
                        var duration = 3 + Math.random() * 2;
                        var size = 8 + Math.random() * 8;
                        var color = colors[Math.floor(Math.random() * colors.length)];

                        confetti.css({
                            'left': left + '%',
                            'background': color,
                            'width': size + 'px',
                            'height': size + 'px',
                            'animation-delay': delay + 's',
                            'animation-duration': duration + 's',
                            'border-radius': Math.random() > 0.5 ? '50%' : '0%'
                        });

                        container.append(confetti);

                        // Hapus confetti setelah animasi selesai
                        setTimeout(function() {
                            confetti.remove();
                        }, (duration + delay) * 1000);
                    }
                }

                // Fungsi untuk menampilkan custom overlay
                function showBirthdayOverlay() {
                    var overlay = $('#birthdayOverlay');
                    if (overlay.length > 0) {
                        // Gunakan window.innerHeight untuk mendapatkan tinggi layar yang tepat
                        var screenHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

                        overlay.css({
                            'position': 'fixed',
                            'top': '0',
                            'left': '0',
                            'right': '0',
                            'bottom': '0',
                            'width': '100%',
                            'height': screenHeight + 'px',
                            'min-height': '100vh',
                            'z-index': '1040'
                        });
                        overlay.addClass('show');
                    }
                }

                // Fungsi untuk menyembunyikan custom overlay
                function hideBirthdayOverlay() {
                    $('#birthdayOverlay').removeClass('show');
                    $('#confetti-container').empty();
                }

                // Fungsi untuk menutup modal - metode langsung dan sederhana
                function closeBirthdayModal() {
                    var modal = $('#birthdayModal');
                    var modalElement = document.getElementById('birthdayModal');

                    // Sembunyikan overlay terlebih dahulu
                    hideBirthdayOverlay();

                    // Metode langsung: sembunyikan semua dengan cara yang pasti
                    // 1. Hapus semua class Bootstrap modal
                    modal.removeClass('show fade in');
                    modal.addClass('fade');

                    // 2. Sembunyikan modal dengan CSS langsung
                    modal.css({
                        'display': 'none !important',
                        'visibility': 'hidden',
                        'opacity': '0',
                        'padding-right': ''
                    });

                    // 3. Sembunyikan modal dialog
                    modal.find('.modal-dialog').css('display', 'none');
                    modal.find('.modal-content').css('display', 'none');

                    // 4. Hapus class modal-open dari body
                    $('body').removeClass('modal-open');
                    $('body').css({
                        'padding-right': '',
                        'overflow': ''
                    });

                    // 5. Hapus semua backdrop
                    $('.modal-backdrop').remove();
                    $('#birthdayOverlay').removeClass('show');

                    // 6. Set atribut style langsung pada element
                    if (modalElement) {
                        modalElement.style.display = 'none';
                        modalElement.style.visibility = 'hidden';
                        modalElement.style.opacity = '0';
                        modalElement.classList.remove('show');
                        modalElement.classList.remove('fade');
                    }

                    // 7. Trigger event hidden untuk kompatibilitas
                    modal.trigger('hidden.bs.modal');

                    // 8. Pastikan sekali lagi setelah beberapa saat
                    setTimeout(function() {
                        modal.hide();
                        modal.css('display', 'none');
                        if (modalElement) {
                            modalElement.style.display = 'none';
                        }
                    }, 50);
                }

                // Event handler menggunakan event delegation untuk memastikan terikat
                $(document).on('click', '.birthday-close-btn', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    closeBirthdayModal();
                });

                // Event handler untuk klik overlay/backdrop - tutup modal saat klik di overlay
                $(document).on('click', '#birthdayOverlay', function(e) {
                    if (e.target === this) {
                        closeBirthdayModal();
                    }
                });

                // Mencegah modal tutup saat klik di dalam modal content
                $(document).on('click', '#birthdayModal .modal-content', function(e) {
                    e.stopPropagation();
                });

                // Event handler untuk tombol "Terima Kasih"
                $(document).on('click', '.birthday-button', function(e) {
                    e.preventDefault();
                    closeBirthdayModal();
                });

                // Coba Bootstrap 5 API dulu, jika tidak ada gunakan jQuery
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    var birthdayModal = new bootstrap.Modal(document.getElementById('birthdayModal'), {
                        backdrop: false,
                        keyboard: false
                    });

                    // Tampilkan overlay sebelum modal
                    showBirthdayOverlay();

                    // Buat confetti
                    createConfetti();

                    // Tampilkan modal
                    birthdayModal.show();

                    // Pastikan overlay tetap muncul setelah modal muncul
                    $('#birthdayModal').on('shown.bs.modal', function() {
                        showBirthdayOverlay();
                        // Buat confetti lagi setelah beberapa detik
                        setTimeout(function() {
                            createConfetti();
                        }, 2000);
                    });

                    // Sembunyikan overlay saat modal ditutup
                    $('#birthdayModal').on('hidden.bs.modal', function() {
                        hideBirthdayOverlay();
                    });
                } else {
                    $('#birthdayModal').modal({
                        backdrop: false,
                        keyboard: false
                    });

                    // Tampilkan overlay sebelum modal
                    showBirthdayOverlay();

                    // Buat confetti
                    createConfetti();

                    // Tampilkan modal
                    $('#birthdayModal').modal('show');

                    // Pastikan overlay tetap muncul setelah modal muncul
                    $('#birthdayModal').on('shown.bs.modal', function() {
                        showBirthdayOverlay();
                        // Buat confetti lagi setelah beberapa detik
                        setTimeout(function() {
                            createConfetti();
                        }, 2000);
                    });

                    // Sembunyikan overlay saat modal ditutup
                    $('#birthdayModal').on('hidden.bs.modal', function() {
                        hideBirthdayOverlay();
                    });
                }

                // Update overlay saat window resize
                $(window).on('resize', function() {
                    if ($('#birthdayModal').hasClass('show')) {
                        showBirthdayOverlay();
                    }
                });
            });
        @endif

        // Handler untuk tombol Daftarkan Wajah - redirect ke halaman baru
        $("#btnDaftarkanWajah").click(function(e) {
            e.preventDefault();
            window.location.href = "{{ route('facerecognition.karyawan.create') }}";
        });
    </script>
@endpush
