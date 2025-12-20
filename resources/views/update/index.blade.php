@extends('layouts.app')
@section('titlepage', 'Update Aplikasi')

@section('content')
@section('navigasi')
    <span>Update Aplikasi</span>
@endsection

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Informasi Versi Aplikasi</h5>
                <button type="button" class="btn btn-primary btn-sm" onclick="checkUpdate()">
                    <i class="ti ti-refresh me-1"></i> Cek Update
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Versi Saat Ini</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-tag"></i></span>
                                <input type="text" class="form-control" value="{{ $currentVersion }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status Aplikasi</label>
                            <div class="mt-2">
                                <span class="badge bg-success fs-6">
                                    <i class="ti ti-check-circle me-1"></i> Aplikasi Aktif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Terakhir Update</label>
                            <div class="mt-2">
                                @php
                                    $lastUpdate = $updateLogs->where('status', 'success')->first();
                                @endphp
                                @if ($lastUpdate)
                                    <span
                                        class="text-muted">{{ $lastUpdate->completed_at ? $lastUpdate->completed_at->format('d/m/Y H:i') : $lastUpdate->created_at->format('d/m/Y H:i') }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" id="updateInfo" style="display: none;">
    <div class="col-12">
        <div class="card mb-4 border-warning">
            <div class="card-header bg-warning bg-opacity-10">
                <h5 class="card-title mb-0">
                    <i class="ti ti-bell-ringing me-2"></i>Update Tersedia
                </h5>
            </div>
            <div class="card-body" id="updateContent">
                <!-- Content akan diisi via AJAX -->
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Riwayat Update</h5>
                <a href="{{ route('update.history') }}" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-list me-1"></i> Lihat Semua
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th width="15%">Versi</th>
                                <th width="15%">Status</th>
                                <th width="20%">User</th>
                                <th width="20%">Tanggal</th>
                                <th width="15%">Versi Sebelumnya</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($updateLogs as $log)
                                <tr>
                                    <td>
                                        <strong>{{ $log->version }}</strong>
                                    </td>
                                    <td>
                                        @if ($log->status == 'success')
                                            <span class="badge bg-success">
                                                <i class="ti ti-check me-1"></i>Berhasil
                                            </span>
                                        @elseif($log->status == 'failed')
                                            <span class="badge bg-danger">
                                                <i class="ti ti-x me-1"></i>Gagal
                                            </span>
                                        @elseif($log->status == 'downloading')
                                            <span class="badge bg-info">
                                                <i class="ti ti-download me-1"></i>Mengunduh
                                            </span>
                                        @elseif($log->status == 'installing')
                                            <span class="badge bg-warning">
                                                <i class="ti ti-settings me-1"></i>Menginstall
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="ti ti-clock me-1"></i>Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    {{ substr($log->user->name ?? 'S', 0, 1) }}
                                                </span>
                                            </div>
                                            <div>{{ $log->user->name ?? '-' }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $log->created_at->format('d/m/Y') }}<br>
                                            <span class="text-muted">{{ $log->created_at->format('H:i') }}</span>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $log->previous_version ?? '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('update.log', $log->id) }}" class="btn btn-sm btn-label-info">
                                            <i class="ti ti-eye me-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-inbox fs-1 text-muted mb-2"></i>
                                            <p class="text-muted mb-0">Belum ada riwayat update</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Progress Modal -->
<div class="modal fade" id="progressModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="ti ti-refresh me-2"></i>Proses Update
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="closeProgressBtn"
                    style="display: none;"></button>
            </div>
            <div class="modal-body">
                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold">Progress</span>
                        <span class="badge bg-primary" id="progressPercentage">0%</span>
                    </div>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" id="progressBar" style="width: 0%"
                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            <span id="progressText">0%</span>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-3">
                    <div class="alert alert-info mb-0" id="statusMessage">
                        <i class="ti ti-info-circle me-2"></i>
                        <span id="statusText">Memulai proses update...</span>
                    </div>
                </div>

                <!-- Terminal Log -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold">
                            <i class="ti ti-terminal me-1"></i>Log Proses
                        </span>
                        <button class="btn btn-sm btn-outline-secondary" onclick="clearTerminal()">
                            <i class="ti ti-trash me-1"></i>Clear
                        </button>
                    </div>
                    <div class="terminal-container bg-dark text-light p-3 rounded"
                        style="height: 300px; overflow-y: auto; font-family: 'Courier New', monospace; font-size: 12px;" id="terminalLog">
                        <div class="text-success">[SYSTEM] Terminal siap...</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelBtn" style="display: none;">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    function checkUpdate() {
        $('#progressModal').modal('show');
        updateProgress(0, 'Mengecek update...', '');

        $.ajax({
            url: '{{ route('update.check') }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#progressModal').modal('hide');

                if (response.has_update) {
                    showUpdateInfo(response);
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Tidak Ada Update',
                        text: 'Aplikasi sudah menggunakan versi terbaru',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr) {
                $('#progressModal').modal('hide');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.error || 'Gagal mengecek update',
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    function showUpdateInfo(data) {
        let html = `
            <div class="alert alert-info border-info mb-3">
                <div class="d-flex align-items-center">
                    <i class="ti ti-info-circle fs-4 me-2"></i>
                    <div>
                        <h6 class="mb-1">Versi Terbaru Tersedia!</h6>
                        <p class="mb-0">Versi Terbaru: <strong>${data.latest_version}</strong> | Versi Saat Ini: <strong>${data.current_version}</strong></p>
                    </div>
                </div>
            </div>
        `;

        if (data.update) {
            html += `
                <div class="mb-4">
                    <h6 class="mb-2">
                        <i class="ti ti-package me-2"></i>
                        ${data.update.title || 'Update ' + data.update.version}
                    </h6>
                    ${data.update.description ? `<p class="text-muted">${data.update.description}</p>` : ''}
                    ${data.update.changelog ? `
                            <div class="mt-3">
                                <h6 class="mb-2">Changelog:</h6>
                                <pre class="bg-light p-3 rounded border" style="max-height: 300px; overflow-y: auto; white-space: pre-wrap;">${data.update.changelog}</pre>
                            </div>
                        ` : ''}
                    ${data.update.file_size ? `
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="ti ti-file-zip me-1"></i>
                                    Ukuran File: ${formatFileSize(data.update.file_size)}
                                </small>
                            </div>
                        ` : ''}
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-primary" onclick="updateNow('${data.update.version}')">
                        <i class="ti ti-download me-1"></i> Update Sekarang
                    </button>
                    <button class="btn btn-outline-secondary" onclick="downloadUpdate('${data.update.version}')">
                        <i class="ti ti-download me-1"></i> Download Saja
                    </button>
                </div>
            `;
        }

        $('#updateContent').html(html);
        $('#updateInfo').show();
        $('html, body').animate({
            scrollTop: $('#updateInfo').offset().top - 100
        }, 500);
    }

    function formatFileSize(bytes) {
        if (!bytes) return '-';
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(2) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
    }

    let progressInterval = null;
    let updateLogId = null;

    function updateNow(version) {
        Swal.fire({
            title: 'Konfirmasi Update',
            text: 'Apakah Anda yakin ingin mengupdate aplikasi sekarang? Pastikan sudah backup database.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Update Sekarang',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Reset progress
                updateProgress(0, 'Memulai proses update...', '');
                $('#progressModal').modal('show');
                $('#cancelBtn').hide();
                $('#closeProgressBtn').hide();

                // Start update process
                $.ajax({
                    url: `/update/${version}/update-now`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.update_log_id) {
                            updateLogId = response.update_log_id;
                            // Start polling progress
                            startProgressPolling(updateLogId);
                        } else {
                            // Fallback jika tidak ada update_log_id
                            setTimeout(() => {
                                checkUpdateComplete(version);
                            }, 2000);
                        }
                    },
                    error: function(xhr) {
                        $('#progressModal').modal('hide');
                        addTerminalLog('ERROR: ' + (xhr.responseJSON?.error || 'Gagal mengupdate aplikasi'), 'error');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.error || 'Gagal mengupdate aplikasi',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    }

    function startProgressPolling(logId) {
        if (progressInterval) {
            clearInterval(progressInterval);
        }

        progressInterval = setInterval(() => {
            $.ajax({
                url: `/update/progress/${logId}`,
                method: 'GET',
                success: function(response) {
                    if (response.success && response.data) {
                        const data = response.data;
                        updateProgress(
                            data.progress_percentage || 0,
                            data.message || data.status,
                            data.progress_log || ''
                        );

                        // Check if completed
                        if (data.status === 'success' || data.status === 'failed') {
                            clearInterval(progressInterval);
                            progressInterval = null;

                            if (data.status === 'success') {
                                $('#cancelBtn').show();
                                $('#closeProgressBtn').show();
                                setTimeout(() => {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: 'Update berhasil diinstall. Halaman akan direload.',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        location.reload();
                                    });
                                }, 1000);
                            } else {
                                $('#cancelBtn').show();
                                $('#closeProgressBtn').show();
                                addTerminalLog('UPDATE GAGAL!', 'error');
                            }
                        }
                    }
                },
                error: function(xhr) {
                    console.error('Error polling progress:', xhr);
                }
            });
        }, 1000); // Poll every 1 second
    }

    function updateProgress(percentage, message, log) {
        // Update progress bar
        $('#progressBar').css('width', percentage + '%').attr('aria-valuenow', percentage);
        $('#progressText').text(percentage + '%');
        $('#progressPercentage').text(percentage + '%');

        // Update status
        $('#statusText').text(message || 'Memproses...');

        // Update terminal log
        if (log) {
            const lines = log.split('\n').filter(line => line.trim());
            $('#terminalLog').empty();
            lines.forEach(line => {
                addTerminalLog(line);
            });
            // Auto scroll to bottom
            const terminal = document.getElementById('terminalLog');
            terminal.scrollTop = terminal.scrollHeight;
        }
    }

    function addTerminalLog(message, type = 'info') {
        const terminal = $('#terminalLog');
        const timestamp = new Date().toLocaleTimeString();
        let className = 'text-light';

        if (type === 'error') {
            className = 'text-danger';
        } else if (type === 'success') {
            className = 'text-success';
        } else if (message.includes('✓') || message.includes('selesai')) {
            className = 'text-success';
        } else if (message.includes('ERROR') || message.includes('Gagal')) {
            className = 'text-danger';
        } else if (message.includes('Memulai') || message.includes('Meng')) {
            className = 'text-info';
        }

        terminal.append(`<div class="${className}">${message}</div>`);

        // Auto scroll to bottom
        const terminalEl = document.getElementById('terminalLog');
        terminalEl.scrollTop = terminalEl.scrollHeight;
    }

    function clearTerminal() {
        $('#terminalLog').html('<div class="text-success">[SYSTEM] Terminal cleared...</div>');
    }

    function checkUpdateComplete(version) {
        // Fallback method if polling doesn't work
        setTimeout(() => {
            $('#progressModal').modal('hide');
            location.reload();
        }, 10000);
    }

    // Cleanup on modal close
    $('#progressModal').on('hidden.bs.modal', function() {
        if (progressInterval) {
            clearInterval(progressInterval);
            progressInterval = null;
        }
    });

    function downloadUpdate(version) {
        $('#progressModal').modal('show');
        updateProgress(0, 'Mengunduh file update...', '');

        $.ajax({
            url: `/update/${version}/download`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#progressModal').modal('hide');
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'File update berhasil diunduh',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message || 'Gagal mengunduh file',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr) {
                $('#progressModal').modal('hide');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.error || 'Gagal mengunduh file update',
                    confirmButtonText: 'OK'
                });
            }
        });
    }
</script>
@endpush
