 <!-- Menu -->

 <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
     <div class="app-brand demo" style="height: 85px !important">
         <a href="{{ route('dashboard.index') }}" class="app-brand-link">
           <span class="app-brand-logo rounded-circle demo d-flex align-items-center justify-content-center"
    style="width: 48px; height: 48px; background: rgba(0, 0, 0, 0);">
    <img src="{{ asset('storage/logo/1765366746.png') }}"
         alt="Logo"
         style="width: 100%; height: 100%; object-fit: contain;">
</span>

             </span>
             <span class="app-brand-text demo menu-text fw-bold d-flex flex-column ms-2" style="letter-spacing: 1px; color: #fff;">
                 <span style="font-size: 12px;">PT. Craze Indonesia</span>
                 <small class="mt-1" style="font-size: 8px; letter-spacing: 0.5px; color: rgba(255, 255, 255, 0.7);">
                     Cikarang Utara, Bekasi.
                 </small>
                 <small class="mt-1" style="font-size: 8px; letter-spacing: 0.5px; color: rgba(255, 255, 255, 0.7);">Version 1.0.0</small>
             </span>
         </a>

         <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
             <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-top mb-4"></i>
             <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
         </a>
     </div>

     @php
         $authUser = auth()->user();
         $fullName = $authUser->name ?? 'Pengguna';
         $userName = explode(' ', $fullName)[0]; // Ambil nama depan saja
         $userEmail = $authUser->email ?? '-';
         $userPhoto = null;
         $userRoleText = $authUser->getRoleNames()->first() ?? 'User';

         $userKaryawan = \App\Models\Userkaryawan::where('id_user', $authUser->id)->first();
         if ($userKaryawan) {
             $sidebarKaryawan = \App\Models\Karyawan::where('nik', $userKaryawan->nik)->first();
             if (
                 $sidebarKaryawan &&
                 $sidebarKaryawan->foto &&
                 \Illuminate\Support\Facades\Storage::disk('public')->exists('/karyawan/' . $sidebarKaryawan->foto)
             ) {
                 $userPhoto = getfotoKaryawan($sidebarKaryawan->foto);
             }
         }
     @endphp

     <div class="px-3 pb-3 py-3">
         <div class="d-flex align-items-center rounded-3 p-3 shadow-sm"
             style="background: linear-gradient(135deg, #ffd60a 0%, #ffc300 100%); border: 1px solid rgba(0,0,0,0.05);">
             <div class="flex-shrink-0 position-relative">
                 @if ($userPhoto)
                     <div class="rounded-circle border border-3 shadow"
                         style="width: 48px; height: 48px; background-image: url('{{ $userPhoto }}'); background-size: cover; background-position: center; border-color: rgba(0,0,0,0.08) !important;">
                     </div>
                 @else
                     <div class="rounded-circle d-flex align-items-center justify-content-center shadow"
                         style="width: 48px; height: 48px; font-size: 22px; background: #fff; color: #ffc300;">
                         <i class="ti ti-user"></i>
                     </div>
                 @endif
             </div>
             <div class="flex-grow-1 ms-3">
                 <div class="fw-bold mb-0" style="font-size: 14px; color: #1a1a1a;">{{ $userName }}</div>
                 <small class="text-uppercase"
                     style="letter-spacing: 0.5px; font-size: 11px; color: rgba(0,0,0,0.6); font-weight: 500;">{{ $userRoleText }}</small>
             </div>
             <a href="#" class="btn btn-sm rounded-2 shadow-sm"
                 style="background: rgba(0,0,0,0.08); border: none; color: #1a1a1a; padding: 6px 12px;" data-bs-toggle="tooltip" title="Edit Profile">
                 <i class="ti ti-settings" style="font-size: 16px;"></i>
             </a>
         </div>
     </div>

     <div class="menu-inner-shadow"></div>

     <ul class="menu-inner py-1">
         <!-- Dashboards -->
         <li class="menu-item {{ request()->is(['dashboard', 'dashboard/*']) ? 'active' : '' }}">
             <a href="{{ route('dashboard.index') }}" class="menu-link">
                 <i class="menu-icon tf-icons ti ti-home"></i>
                 <div>Dashboard</div>
             </a>
         </li>
         @can('trackingpresensi.index')
             <li class="menu-item {{ request()->is(['trackingpresensi', 'trackingpresensi/*']) ? 'active' : '' }}">
                 <a href="{{ route('trackingpresensi.index') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-map-pin"></i>
                     <div>Tracking Presensi</div>
                 </a>
             </li>
         @endcan
         @can('aktivitaskaryawan.index')
             <li class="menu-item {{ request()->is(['aktivitaskaryawan', 'aktivitaskaryawan/*']) ? 'active' : '' }}">
                 <a href="{{ route('aktivitaskaryawan.index') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-activity"></i>
                     <div>Aktivitas Karyawan</div>
                 </a>
             </li>
         @endcan
         @can('kunjungan.index')
             <li class="menu-item {{ request()->is(['kunjungan', 'kunjungan/*']) ? 'active' : '' }}">
                 <a href="{{ route('kunjungan.index') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-map-pin"></i>
                     <div>Kunjungan</div>
                 </a>
             </li>
         @endcan

         @can('kunjungan.index')
             <li class="menu-item {{ request()->is(['tracking-kunjungan', 'tracking-kunjungan/*']) ? 'active' : '' }}">
                 <a href="{{ route('tracking-kunjungan.index') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-map-2"></i>
                     <div>Tracking Kunjungan</div>
                 </a>
             </li>
         @endcan
         @can('kontrak.index')
             <li class="menu-item {{ request()->is(['kontrak', 'kontrak/*']) ? 'active' : '' }}">
                 <a href="{{ route('kontrak.index') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-file-certificate"></i>
                     <div>Kontrak</div>
                 </a>
             </li>
         @endcan

         @if (auth()->user()->hasAnyPermission(['karyawan.index', 'departemen.index', 'cabang.index', 'cuti.index', 'jabatan.index', 'grup.index']))
             <li
                 class="menu-item {{ request()->is(['karyawan', 'karyawan/*', 'departemen', 'departemen/*', 'cabang', 'cuti', 'jabatan', 'grup', 'grup/*']) ? 'open' : '' }}">
                 <a href="javascript:void(0);" class="menu-link menu-toggle">
                     <i class="menu-icon tf-icons ti ti-database"></i>
                     <div>Data Master</div>

                 </a>
                 <ul class="menu-sub">
                     @can('karyawan.index')
                         <li class="menu-item {{ request()->is(['karyawan', 'karyawan/*']) ? 'active' : '' }}">
                             <a href="{{ route('karyawan.index') }}" class="menu-link">
                                 <div>Karyawan</div>
                             </a>
                         </li>
                     @endcan
                     @can('departemen.index')
                         <li class="menu-item {{ request()->is(['departemen', 'departemen/*']) ? 'active' : '' }}">
                             <a href="{{ route('departemen.index') }}" class="menu-link">
                                 <div>Departemen</div>
                             </a>
                         </li>
                     @endcan
                     @can('grup.index')
                         <li class="menu-item {{ request()->is(['grup', 'grup/*']) ? 'active' : '' }}">
                             <a href="{{ route('grup.index') }}" class="menu-link">
                                 <div>Grup</div>
                             </a>
                         </li>
                     @endcan
                     @can('jabatan.index')
                         <li class="menu-item {{ request()->is(['jabatan', 'jabatan/*']) ? 'active' : '' }}">
                             <a href="{{ route('jabatan.index') }}" class="menu-link">
                                 <div>Jabatan</div>
                             </a>
                         </li>
                     @endcan
                     @can('cabang.index')
                         <li class="menu-item {{ request()->is(['cabang', 'cabang/*']) ? 'active' : '' }}">
                             <a href="{{ route('cabang.index') }}" class="menu-link">
                                 <div>Cabang</div>
                             </a>
                         </li>
                     @endcan
                     @can('cuti.index')
                         <li class="menu-item {{ request()->is(['cuti', 'cuti/*']) ? 'active' : '' }}">
                             <a href="{{ route('cuti.index') }}" class="menu-link">
                                 <div>Cuti</div>
                             </a>
                         </li>
                     @endcan


                 </ul>
             </li>
         @endif
         @if (auth()->user()->hasAnyPermission([
                     'gajipokok.index',
                     'jenistunjangan.index',
                     'tunjangan.index',
                     'bpjskesehatan.index',
                     'bpjstenagakerja.index',
                     'penyesuaiangaji.index',
                 ]))
             <li
                 class="menu-item {{ request()->is([
                     'gajipokok',
                     'jenistunjangan',
                     'tunjangan',
                     'bpjskesehatan',
                     'bpjstenagakerja',
                     'penyesuaiangaji',
                     'penyesuaiangaji/*',
                     'slipgaji',
                     'slipgaji/*',
                 ])
                     ? 'open'
                     : '' }}">
                 <a href="javascript:void(0);" class="menu-link menu-toggle">
                     <i class="menu-icon tf-icons ti ti-moneybag"></i>
                     <div>Payroll</div>

                 </a>
                 <ul class="menu-sub">
                     @can('jenistunjangan.index')
                         <li class="menu-item {{ request()->is(['jenistunjangan', 'jenistunjangan/*']) ? 'active' : '' }}">
                             <a href="{{ route('jenistunjangan.index') }}" class="menu-link">
                                 <div>Jenis Tunjangan</div>
                             </a>
                         </li>
                     @endcan
                     @can('gajipokok.index')
                         <li class="menu-item {{ request()->is(['gajipokok', 'gajipokok/*']) ? 'active' : '' }}">
                             <a href="{{ route('gajipokok.index') }}" class="menu-link">
                                 <div>Gaji Pokok</div>
                             </a>
                         </li>
                     @endcan
                     @can('tunjangan.index')
                         <li class="menu-item {{ request()->is(['tunjangan', 'tunjangan/*']) ? 'active' : '' }}">
                             <a href="{{ route('tunjangan.index') }}" class="menu-link">
                                 <div>Tunjangan</div>
                             </a>
                         </li>
                     @endcan
                     @can('bpjskesehatan.index')
                         <li class="menu-item {{ request()->is(['bpjskesehatan', 'bpjskesehatan/*']) ? 'active' : '' }}">
                             <a href="{{ route('bpjskesehatan.index') }}" class="menu-link">
                                 <div>BPJS Kesehatan</div>
                             </a>
                         </li>
                     @endcan
                     @can('bpjstenagakerja.index')
                         <li class="menu-item {{ request()->is(['bpjstenagakerja', 'bpjstenagakerja/*']) ? 'active' : '' }}">
                             <a href="{{ route('bpjstenagakerja.index') }}" class="menu-link">
                                 <div>BPJS Tenaga Kerja</div>
                             </a>
                         </li>
                     @endcan
                     @can('penyesuaiangaji.index')
                         <li class="menu-item {{ request()->is(['penyesuaiangaji', 'penyesuaiangaji/*']) ? 'active' : '' }}">
                             <a href="{{ route('penyesuaiangaji.index') }}" class="menu-link">
                                 <div>Penyesuaian Gaji</div>
                             </a>
                         </li>
                     @endcan
                     @can('slipgaji.index')
                         <li class="menu-item {{ request()->is(['slipgaji', 'slipgaji/*']) ? 'active' : '' }}">
                             <a href="{{ route('slipgaji.index') }}" class="menu-link">
                                 <div>Slip Gaji</div>
                             </a>
                         </li>
                     @endcan
                 </ul>
             </li>
         @endif
         @if (auth()->user()->hasAnyPermission(['presensi.index']))
             <li class="menu-item {{ request()->is(['presensi', 'presensi/*']) ? 'active' : '' }}">
                 <a href="{{ route('presensi.index') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-device-desktop"></i>
                     <div>Monitoring Presensi</div>
                 </a>
             </li>
         @endif

         @if (auth()->user()->hasAnyPermission(['izinabsen.index', 'izinsakit.index', 'izincuti.index', 'izindinas.index']))
             <li class="menu-item {{ request()->is(['izinabsen', 'izinabsen/*', 'izinsakit', 'izincuti', 'izindinas']) ? 'active' : '' }}">
                 <a href="{{ route('izinabsen.index') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-folder-check"></i>
                     <div>Pengajuan Absen</div>
                     @if (!empty($notifikasi_ajuan_absen))
                         <div class="badge bg-danger rounded-pill ms-auto">{{ $notifikasi_ajuan_absen }}</div>
                     @endif
                 </a>
             </li>
         @endif
         @if (auth()->user()->hasAnyPermission(['lembur.index']))
             <li class="menu-item {{ request()->is(['lembur', 'lembur/*']) ? 'active' : '' }}">
                 <a href="{{ route('lembur.index') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-clock"></i>
                     <div>Lembur</div>
                     @if (!empty($notifikasi_lembur))
                         <div class="badge bg-danger rounded-pill ms-auto">{{ $notifikasi_lembur }}</div>
                     @endif
                 </a>
             </li>
         @endif
        @if (auth()->user()->hasRole(['super admin']))
            <li
                class="menu-item {{ request()->is(['harilibur', 'harilibur/*', 'jamkerjabydept', 'jamkerjabydept/*', 'generalsetting', 'denda', 'jamkerja', 'jamkerja/*']) ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-settings"></i>
                    <div>Konfigurasi</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is(['generalsetting', 'generalsetting/*']) ? 'active' : '' }}">
                        <a href="{{ route('generalsetting.index') }}" class="menu-link">
                            <div>General Setting</div>
                        </a>
                    </li>
                    @if ($general_setting->denda)
                        <li class="menu-item {{ request()->is(['denda', 'denda/*']) ? 'active' : '' }}">
                            <a href="{{ route('denda.index') }}" class="menu-link">
                                <div>Denda</div>
                            </a>
                        </li>
                    @endif

                    <li class="menu-item {{ request()->is(['harilibur', 'harilibur/*']) ? 'active' : '' }}">
                        <a href="{{ route('harilibur.index') }}" class="menu-link">
                            <div>Hari Libur</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is(['jamkerjabydept', 'jamkerjabydept/*']) ? 'active' : '' }}">
                        <a href="{{ route('jamkerjabydept.index') }}" class="menu-link">
                            <div>Jam Kerja Departemen</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is(['jamkerja', 'jamkerja/*']) ? 'active' : '' }}">
                        <a href="{{ route('jamkerja.index') }}" class="menu-link">
                            <div>Jam Kerja</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif
         @if (auth()->user()->hasAnyPermission(['laporan.presensi']))
             <li class="menu-item {{ request()->is(['laporan', 'laporan/*']) ? 'open' : '' }} ">
                 <a href="javascript:void(0);" class="menu-link menu-toggle">
                     <i class="menu-icon tf-icons ti ti-adjustments-alt"></i>
                     <div>Laporan</div>
                 </a>
                 <ul class="menu-sub">
                     <li class="menu-item {{ request()->is(['laporan/presensi']) ? 'active' : '' }}">
                         <a href="{{ route('laporan.presensi') }}" class="menu-link">
                             <div>Presensi & Gaji</div>
                         </a>
                     </li>
                 </ul>
             </li>
         @endif
         @if (auth()->user()->hasRole(['super admin']))
             <li
                 class="menu-item {{ request()->is(['roles', 'roles/*', 'permissiongroups', 'permissiongroups/*', 'permissions', 'permissions/*', 'users', 'users/*', 'bersihkanfoto', 'bersihkanfoto/*', 'resetdata', 'resetdata/*']) ? 'open' : '' }} ">
                 <a href="javascript:void(0);" class="menu-link menu-toggle">
                     <i class="menu-icon tf-icons ti ti-adjustments-alt"></i>
                     <div>Utilities</div>
                 </a>
                 <ul class="menu-sub">
                     <li class="menu-item {{ request()->is(['users', 'users/*']) ? 'active' : '' }}">
                         <a href="{{ route('users.index') }}" class="menu-link">
                             <div>User</div>
                         </a>
                     </li>
                     <li class="menu-item {{ request()->is(['roles', 'roles/*']) ? 'active' : '' }}">
                         <a href="{{ route('roles.index') }}" class="menu-link">
                             <div>Role</div>
                         </a>
                     </li>
                     <li class="menu-item {{ request()->is(['permissions', 'permissions/*']) ? 'active' : '' }}"">
                         <a href="{{ route('permissions.index') }}" class="menu-link">
                             <div>Permission</div>
                         </a>
                     </li>
                     <li class="menu-item  {{ request()->is(['permissiongroups', 'permissiongroups/*']) ? 'active' : '' }}">
                         <a href="{{ route('permissiongroups.index') }}" class="menu-link">
                             <div>Group Permission</div>
                         </a>
                     </li>
                     @can('bersihkanfoto.index')
                         <li class="menu-item {{ request()->is(['bersihkanfoto', 'bersihkanfoto/*']) ? 'active' : '' }}">
                             <a href="{{ route('bersihkanfoto.index') }}" class="menu-link">
                                 <div>Bersihkan Foto</div>
                             </a>
                         </li>
                     @endcan
                     <li class="menu-item {{ request()->is(['resetdata', 'resetdata/*']) ? 'active' : '' }}">
                         <a href="{{ route('resetdata.index') }}" class="menu-link">
                             <div>Reset Data</div>
                         </a>
                     </li>
                 </ul>
             </li>
         @endif
         @if (auth()->user()->hasRole(['super admin']))
             <li class="menu-item {{ request()->is(['wagateway', 'wagateway/*']) ? 'active' : '' }}">
                 <a href="{{ route('wagateway.index') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-brand-whatsapp"></i>
                     <div>WA Gateway</div>
                 </a>
             </li>
         @endif
     </ul>
 </aside>
 <!-- / Menu -->
