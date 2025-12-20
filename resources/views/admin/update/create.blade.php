@extends('layouts.app')
@section('titlepage', 'Tambah Update')

@section('content')
@section('navigasi')
    <span>Tambah Update</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('admin.update.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-2"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.update.store') }}" method="POST" id="formUpdate">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <x-input-with-icon label="Versi" name="version" icon="ti ti-tag" required />
                            <small class="text-muted">Format: 1.0.0, 1.0.1, 1.1.0, 2.0.0</small>
                        </div>
                        <div class="col-md-6">
                            <x-input-with-icon label="Judul Update" name="title" icon="ti ti-heading" required />
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <x-textarea-label label="Deskripsi" name="description" icon="ti ti-file-text" />
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <x-textarea-label label="Changelog" name="changelog" icon="ti ti-list" />
                            <small class="text-muted">Gunakan format list, contoh: - Fix bug\n- New feature</small>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-8">
                            <x-input-with-icon label="File URL" name="file_url" icon="ti ti-link" required />
                            <small class="text-muted">URL lengkap ke file ZIP update</small>
                        </div>
                        <div class="col-md-4">
                            <x-input-with-icon label="Ukuran File (bytes)" name="file_size" icon="ti ti-file-zip" type="number" />
                            <small class="text-muted">Contoh: 5242880 (5 MB)</small>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <x-input-with-icon label="Checksum (MD5)" name="checksum" icon="ti ti-key" />
                            <small class="text-muted">Opsional, untuk validasi file</small>
                        </div>
                        <div class="col-md-6">
                            <x-input-with-icon label="Tanggal Rilis" name="released_at" icon="ti ti-calendar" type="date" />
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <x-input-with-icon label="Migrations (comma separated)" name="migrations" icon="ti ti-database" />
                            <small class="text-muted">Contoh: 2024_01_01_migration.php, 2024_01_02_migration.php</small>
                        </div>
                        <div class="col-md-6">
                            <x-input-with-icon label="Seeders (comma separated)" name="seeders" icon="ti ti-seeding" />
                            <small class="text-muted">Contoh: NewSeeder, AnotherSeeder</small>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_major" id="is_major" value="1">
                                <label class="form-check-label" for="is_major">
                                    <strong>Update Major</strong>
                                </label>
                                <small class="d-block text-muted">Update major biasanya breaking changes</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                                <label class="form-check-label" for="is_active">
                                    <strong>Aktif</strong>
                                </label>
                                <small class="d-block text-muted">Update aktif akan muncul untuk user</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button class="btn btn-primary w-100" type="submit">
                            <i class="ti ti-send me-1"></i> Simpan Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection











