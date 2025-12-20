@extends('layouts.app')
@section('titlepage', 'Kontrak')

@section('content')
@section('navigasi')
    <span>Kontrak</span>
@endsection

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                @can('kontrak.create')
                    <a href="javascript:void(0);" class="btn btn-primary" id="btnCreateKontrak">
                        <i class="fa fa-plus me-2"></i> Tambah Kontrak
                    </a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('kontrak.index') }}">
                            <div class="row">
                                <div class="col-lg-4 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Cari Nama Karyawan" name="nama_karyawan" icon="ti ti-search"
                                        value="{{ request('nama_karyawan') }}" placeholder="Masukkan nama karyawan" hideLabel />
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <select class="form-select" name="kode_cabang">
                                        <option value="">Semua Cabang</option>
                                        @foreach ($cabangs as $cabang)
                                            <option value="{{ $cabang->kode_cabang }}"
                                                {{ request('kode_cabang') == $cabang->kode_cabang ? 'selected' : '' }}>
                                                {{ $cabang->nama_cabang }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <select class="form-select" name="kode_dept">
                                        <option value="">Semua Departemen</option>
                                        @foreach ($departemens as $dept)
                                            <option value="{{ $dept->kode_dept }}" {{ request('kode_dept') == $dept->kode_dept ? 'selected' : '' }}>
                                                {{ $dept->nama_dept }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 col-sm-12 col-md-12 d-flex gap-2">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="ti ti-search me-1"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>No.</th>
                                <th>No Kontrak</th>
                                <th>NIK</th>
                                <th>Nama Karyawan</th>
                                <th>Tanggal</th>
                                <th>Dari</th>
                                <th>Sampai</th>
                                <th>Jabatan</th>
                                <th>Cabang</th>
                                <th>Departemen</th>
                                <th>Status Kontrak</th>
                                <th class="text-center">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kontraks as $kontrak)
                                <tr>
                                    <td>{{ $loop->iteration + ($kontraks->currentPage() - 1) * $kontraks->perPage() }}</td>
                                    <td class="fw-semibold">{{ $kontrak->no_kontrak }}</td>
                                    <td>{{ $kontrak->nik }}</td>
                                    <td>{{ $kontrak->nama_karyawan ?? '-' }}</td>
                                    <td>{{ date('d/m/Y', strtotime($kontrak->tanggal)) ?? '-' }}</td>
                                    <td>{{ date('d/m/Y', strtotime($kontrak->dari)) ?? '-' }}</td>
                                    <td>{{ date('d/m/Y', strtotime($kontrak->sampai)) ?? '-' }}</td>
                                    <td>{{ $kontrak->nama_jabatan ?? '-' }}</td>
                                    <td>{{ $kontrak->nama_cabang ?? '-' }}</td>
                                    <td>{{ $kontrak->nama_dept ?? '-' }}</td>
                                    <td>
                                        @if ($kontrak->status_kontrak == '1')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Non Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            @can('kontrak.edit')
                                                <div>
                                                    <a href="#" class="me-2 btnEditKontrak" data-id="{{ Crypt::encrypt($kontrak->id) }}">
                                                        <i class="ti ti-edit text-success"></i>
                                                    </a>
                                                </div>
                                            @endcan
                                            <div>
                                                <a href="{{ route('kontrak.print', Crypt::encrypt($kontrak->id)) }}" target="_blank" class="me-2">
                                                    <i class="ti ti-printer text-primary"></i>
                                                </a>
                                            </div>
                                            @can('kontrak.delete')
                                                <div>
                                                    <form method="POST" name="deleteform" class="deleteform me-1"
                                                        action="{{ route('kontrak.delete', Crypt::encrypt($kontrak->id)) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="#" class="delete-confirm ml-1">
                                                            <i class="ti ti-trash text-danger"></i>
                                                        </a>
                                                    </form>
                                                </div>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="16" class="text-center text-muted">Belum ada data kontrak.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $kontraks->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modalKontrak" show="loadModalKontrak" />
@endsection

@push('myscript')
<script>
    $(function() {
        const modal = $("#modalKontrak");

        function loadingModal() {
            $("#loadModalKontrak").html(`<div class="sk-wave sk-primary mx-auto">
                    <div class="sk-wave-rect"></div>
                    <div class="sk-wave-rect"></div>
                    <div class="sk-wave-rect"></div>
                    <div class="sk-wave-rect"></div>
                    <div class="sk-wave-rect"></div>
                </div>`);
        }

        $("#btnCreateKontrak").on('click', function() {
            loadingModal();
            modal.modal('show');
            $(".modal-title").text('Tambah Kontrak');
            $("#loadModalKontrak").load("{{ route('kontrak.create') }}");
        });

        $(".btnEditKontrak").on('click', function() {
            const id = $(this).data('id');
            loadingModal();
            modal.modal('show');
            $(".modal-title").text('Edit Kontrak');
            $("#loadModalKontrak").load(`/kontrak/${id}/edit`);
        });

        $(".delete-confirm").on('click', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus kontrak?',
                text: 'Data yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        });
    });
</script>
@endpush
