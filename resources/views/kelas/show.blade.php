@extends('layouts.app')

@section('title')
    Detail Kelas: {{ $kelas->nama_kelas }}
@endsection

{{-- [BARU] CSS Kustom untuk Gradebook --}}
@push('css')
<style>
    .gradebook-table th, .gradebook-table td {
        vertical-align: middle;
        text-align: center;
        min-width: 170px; /* Lebar minimum per kolom tugas */
        padding: 0.5rem;
    }
    .gradebook-table th:first-child, .gradebook-table td:first-child {
        text-align: left;
        min-width: 220px; /* Lebar kolom nama */
        position: sticky;
        left: 0;
        background-color: #f8f9fa; /* Warna agar 'fixed' */
        z-index: 1;
    }
    .gradebook-table thead th {
        position: sticky;
        top: 0; /* Header tabel 'fixed' saat scroll vertikal */
        background-color: #e9ecef;
        z-index: 2;
    }
    /* Style untuk setiap sel nilai */
    .grade-cell {
        display: block;
        padding: 0.75rem 0.5rem;
        text-decoration: none;
        color: #212529;
        border-radius: 4px;
        background-color: #fff;
        border: 1px dashed #ced4da;
        min-height: 50px;
    }
    .grade-cell:hover {
        background-color: #f1f3f5;
        border-color: #0d6efd;
        cursor: pointer;
    }
    /* Sel yang sudah dinilai */
    .grade-cell.graded {
        background-color: #e6f7ff;
        border-color: #b3e0ff;
        border-style: solid;
        font-weight: bold;
    }
    /* Sel yang sudah dikerjakan siswa tapi belum dinilai */
    .grade-cell.completed {
        background-color: #f0fff4;
        border-style: solid;
        border-color: #28a745;
    }

    .post-highlight {
        background-color: #fff3cd !important; /* Warna kuning 'warning' Bootstrap */
        border: 1px solid #ffeeba;
        box-shadow: 0 0 10px rgba(255, 193, 7, 0.3);
    }

    /* [BARU] CSS untuk Tampilan Forum di Modal */
    .forum-column {
        padding: 8px;
        border: 1px solid #eee;
        border-radius: 5px;
        height: 400px; /* Beri tinggi tetap agar bisa scroll internal */
        overflow-y: auto;
        background: #fdfdfd;
    }
    .forum-post {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
        background: #fff;
    }
    .forum-reply {
        border-top: 1px dashed #ccc;
        padding: 8px 8px 8px 15px; /* Indentasi balasan */
        margin-top: 8px;
        margin-left: 10px;
        background: #f9f9f9;
    }
    .post-author {
        font-weight: bold;
        color: #0d6efd;
    }
    .reply-author {
        font-weight: bold;
        color: #555;
    }
</style>
@endpush


@section('content')
<div class="container-fluid">

    {{-- [DARI KODE ANDA] Info Kelas (Tidak Berubah) --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="card-title">{{ $kelas->nama_kelas }}</h2>
            <p class="text-muted mb-1">Modul: <strong>{{ $kelas->modul->judul }}</strong></p>
            <p class="text-muted mb-1">Guru Pengajar: <strong>{{ $kelas->guru?->name }}</strong></p>
            <p class="text-muted mb-0">Jumlah Peserta: <strong>{{ $kelas->peserta?->count() }} Siswa</strong></p>
        </div>
    </div>

    {{-- [BARU] Navigasi Tab --}}
    <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary-pane" type="button" role="tab" aria-controls="summary-pane" aria-selected="true">
                <i class="fa fa-th-large me-2"></i> Ringkasan & Manajemen
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="gradebook-tab" data-bs-toggle="tab" data-bs-target="#gradebook-pane" type="button" role="tab" aria-controls="gradebook-pane" aria-selected="false">
                <i class="fa fa-check-square-o me-2"></i> Buku Nilai (Gradebook)
            </button>
        </li>
    </ul>

    {{-- [BARU] Konten Tab --}}
    <div class="tab-content" id="myTabContent">

        {{-- =================================== --}}
        {{-- TAB 1: RINGKASAN (Manajemen Anda) --}}
        {{-- =================================== --}}
        <div class="tab-pane fade show active" id="summary-pane" role="tabpanel" aria-labelledby="summary-tab" tabindex="0">
            <div class="card shadow-sm border-top-0 rounded-0 rounded-bottom">
                <div class="card-body p-4">
                    {{-- [DARI KODE ANDA] Kartu Navigasi --}}
                    <div class="row">
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center d-flex flex-column justify-content-between">
                                    <div>
                                        <i class="fa fa-users fa-3x text-primary mb-3"></i>
                                        <h5 class="card-title">Manajemen Peserta</h5>
                                        <p class="card-text text-muted">Tambah atau hapus siswa dari kelas ini.</p>
                                    </div>
                                    <a href="{{ route('kelas.peserta', $kelas->id) }}" class="btn btn-primary mt-3">
                                        Kelola Peserta
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center d-flex flex-column justify-content-between">
                                    <div>
                                        <i class="fa fa-comments fa-3x text-success mb-3"></i>
                                        <h5 class="card-title">Manajemen Forum</h5>
                                        <p class="card-text text-muted">Atur tim Pro dan Kontra untuk sub-modul debat.</p>
                                    </div>
                                    <a href="{{ route('kelas.forums', $kelas->id) }}" class="btn btn-success mt-3">
                                        Atur Forum
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Kartu Laporan & Nilai dihapus karena sudah menjadi tab sendiri --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- =================================== --}}
        {{-- TAB 2: BUKU NILAI (Gradebook)     --}}
        {{-- =================================== --}}
        <div class="tab-pane fade" id="gradebook-pane" role="tabpanel" aria-labelledby="gradebook-tab" tabindex="0">
            <div class="card shadow-sm border-top-0 rounded-0 rounded-bottom">
                <div class="card-body">
                    <div class="table-responsive">

                        {{-- Hitung total poin maks --}}
                        @php
                            $totalMaxPoints = $subModules->sum('max_points');
                        @endphp

                        <table class="table table-bordered table-hover gradebook-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Siswa</th>

                                    {{-- Loop 1: Buat Kolom Header (Tugas) --}}
                                    @foreach($subModules as $subModule)
                                        <th>
                                            {{ $subModule->title }}
                                            <span class="badge bg-secondary">
                                                {{ $subModule->max_points }} Poin
                                            </span>
                                        </th>
                                    @endforeach

                                    <th>
                                        Total Skor
                                        <span class="badge bg-primary">
                                            {{ $totalMaxPoints }} Poin
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Loop 2: Buat Baris (Siswa) --}}
                                @forelse($students as $student)
                                    @php
                                        $studentTotalScore = 0; // Reset skor total
                                    @endphp
                                    <tr>
                                        <td>{{ $student->name }}</td>

                                        {{-- Loop 3 (Bersarang): Buat Sel Nilai --}}
                                        @foreach($subModules as $subModule)
                                            @php
                                                $key = $student->id . '_' . $subModule->id;
                                                $progress = $allProgress->get($key);

                                                $cellClass = '';
                                                $cellText = 'Beri Nilai';

                                                if ($progress) {
                                                    if ($progress->score !== null) {
                                                        $cellClass = 'graded';
                                                        $cellText = $progress->score . ' / ' . $subModule->max_points;
                                                        $studentTotalScore += $progress->score;
                                                    } elseif ($progress->completed_at) {
                                                        $cellClass = 'completed';
                                                        $cellText = '<i class="fa fa-check-circle text-success"></i> Selesai';
                                                    } else {
                                                        $cellClass = 'draft';
                                                        $cellText = 'Draf Tersimpan';
                                                    }
                                                } else {
                                                    $cellClass = 'pending';
                                                    $cellText = 'Belum Dikerjakan';
                                                }
                                            @endphp

                                            <td>
                                                <a href="#"
                                                   class="grade-cell {{ $cellClass }}"
                                                   id="cell-{{ $student->id }}-{{ $subModule->id }}"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#gradingModal"
                                                   data-student-id="{{ $student->id }}"
                                                   data-student-name="{{ $student->name }}"
                                                   data-submodule-id="{{ $subModule->id }}"
                                                   data-submodule-title="{{ $subModule->title }}"
                                                   data-submodule-type="{{ $subModule->type }}"
                                                   data-max-points="{{ $subModule->max_points }}"
                                                   data-current-score="{{ $progress->score ?? '' }}"
                                                   data-current-feedback="{{ $progress->feedback ?? '' }}"
                                                   >
                                                    {!! $cellText !!}
                                                </a>
                                            </td>
                                        @endforeach

                                        <td>
                                            <strong class="fs-5" id="total-score-{{ $student->id }}">
                                                {{ $studentTotalScore }} / {{ $totalMaxPoints }}
                                            </strong>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $subModules->count() + 2 }}" class="text-center p-4">
                                            Belum ada siswa di kelas ini. Kelola peserta terlebih dahulu.
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
</div>

{{-- [BARU] Modal Penilaian (Disembunyikan) --}}
<div class="modal fade" id="gradingModal" tabindex="-1" aria-labelledby="gradingModalLabel" aria-hidden="true">

    {{-- [PERUBAHAN 1] Tambahkan class 'modal-dialog-scrollable' --}}
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gradingModalLabel">Beri Nilai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Info Siswa & Tugas (diisi oleh JS) --}}
                <h6>Siswa: <strong id="modal-student-name"></strong></h6>
                <h6>Tugas: <strong id="modal-submodule-title"></strong></h6>
                <hr>

                {{-- [PERUBAHAN 2] Hapus style 'max-height' dan 'overflow-y' --}}
                {{-- Kita biarkan border dan bg-light agar tetap menarik --}}
                <div id="modal-submission-content" class="mb-3 p-3 border" style="background-color: #f8f9fa;">
                    <p class="text-muted text-center">Memuat data submission siswa...</p>
                </div>

                {{-- Form Penilaian --}}
                <form id="gradingForm">
                    @csrf
                    <input type="hidden" id="modal-student-id" name="student_id">
                    <input type="hidden" id="modal-submodule-id" name="sub_module_id">
                    <input type="hidden" id="modal-kelas-id" name="kelas_id" value="{{ $kelas->id }}">

                    <div class="mb-3">
                        <label for="modal-score" class="form-label">
                            Skor (Poin Maks: <span id="modal-max-points"></span>)
                        </label>
                        <input type="number" class="form-control" id="modal-score" name="score" min="0">
                    </div>

                    <div class="mb-3">
                        <label for="modal-feedback" class="form-label">Umpan Balik (Feedback)</label>
                        <textarea class="form-control" id="modal-feedback" name="feedback" rows="4"></textarea>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="saveGradeButton">Simpan Nilai</button>
            </div>
        </div>
    </div>
</div>

@endsection
@push('js')
<script>
$(document).ready(function() {
    var gradingModal = new bootstrap.Modal(document.getElementById('gradingModal'));
    var modalEl = document.getElementById('gradingModal');
    var totalMaxPoints = {{ $totalMaxPoints }};
    var myChart = null;

    // ===================================================================
    // FUNGSI HELPER UNTUK GRAFIK (TIDAK BERUBAH)
    // ===================================================================

    function fetchAndParseCsv(url, label, delimiter) {
        return new Promise((resolve, reject) => {
            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('File ' + label + ' gagal dimuat.');
                    return response.text();
                })
                .then(csvText => {
                    Papa.parse(csvText, {
                        skipEmptyLines: true,
                        delimiter: delimiter,
                        complete: function(results) {
                            resolve({ label: label, csvData: results.data });
                        },
                        error: (err) => reject(new Error('Gagal mem-parsing ' + label + ': ' + err.message))
                    });
                })
                .catch(err => reject(err));
        });
    }

    function drawChartFromParsedData(ctx, allParsedData) {
        var colorPalette = [
            'rgb(75, 192, 192)', 'rgb(255, 99, 132)', 'rgb(54, 162, 235)',
            'rgb(255, 206, 86)', 'rgb(153, 102, 255)', 'rgb(255, 159, 64)'
        ];
        var xLabel = (allParsedData[0].csvData.length > 0) ? allParsedData[0].csvData[0][0] : 'Kolom 1';
        var yLabel = (allParsedData[0].csvData.length > 0) ? allParsedData[0].csvData[0][1] : 'Kolom 2';
        var datasets = [];

        allParsedData.forEach((parsedFile, index) => {
            var dataRows = parsedFile.csvData.slice(1);
            var xyData = dataRows.map(row => ({
                x: parseFloat(row[0]),
                y: parseFloat(row[1])
            }));
            var color = colorPalette[index % colorPalette.length];
            datasets.push({
                label: parsedFile.label,
                data: xyData,
                borderColor: color,
                backgroundColor: color + '33',
                tension: 0.1,
                pointRadius: 0
            });
        });

        if (myChart) { myChart.destroy(); }

        myChart = new Chart(ctx, {
            type: 'line',
            data: { datasets: datasets },
            options: {
                animation: false, responsive: true, maintainAspectRatio: false,
                scales: {
                    x: { type: 'linear', title: { display: true, text: xLabel } },
                    y: { title: { display: true, text: yLabel } }
                },
                plugins: {
                    tooltip: { mode: 'index', intersect: false },
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    function loadChart(button) {
        var $button = $(button);
        var datasets = $button.data('json');
        var ctx = document.getElementById('gradebookChartCanvas').getContext('2d');

        if (datasets && datasets.length > 0) {
            $button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memuat Data...');

            var fetchPromises = [];
            datasets.forEach(function(dataset) {
                fetchPromises.push(
                    fetchAndParseCsv(dataset.url, dataset.label, ";")
                );
            });

            Promise.all(fetchPromises)
                .then(allParsedData => {
                    drawChartFromParsedData(ctx, allParsedData);
                    $button.prop('disabled', false).html('<i class="fa fa-sync-alt"></i> Muat / Ulangi Grafik');
                })
                .catch(error => {
                    console.error('Gagal menggambar chart:', error);
                    $button.prop('disabled', false).html('<i class="fa fa-exclamation-triangle"></i> Coba Lagi');
                });
        }
    }

    // ===================================================================
    // EVENT LISTENER MODAL
    // ===================================================================

    // 1. SAAT MODAL AKAN DITAMPILKAN (Tidak Berubah)
    modalEl.addEventListener('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var studentId = button.data('student-id');
        var studentName = button.data('student-name');
        var subModuleId = button.data('submodule-id');
        var subModuleTitle = button.data('submodule-title');
        var subModuleType = button.data('submodule-type');
        var maxPoints = button.data('max-points');
        var currentScore = button.data('current-score');
        var currentFeedback = button.data('current-feedback');

        var modal = $(this);

        if (myChart) { myChart.destroy(); }

        modal.find('#modal-student-name').text(studentName);
        modal.find('#modal-submodule-title').text(subModuleTitle + ' (' + subModuleType + ')');
        modal.find('#modal-max-points').text(maxPoints);
        modal.find('#modal-student-id').val(studentId);
        modal.find('#modal-submodule-id').val(subModuleId);
        modal.find('#modal-score').val(currentScore);
        modal.find('#modal-score').attr('max', maxPoints);
        modal.find('#modal-feedback').val(currentFeedback);

        var submissionContent = modal.find('#modal-submission-content');
        submissionContent.html('<p class="text-muted text-center"><i class="fa fa-spinner fa-spin"></i> Memuat data submission siswa...</p>');

        $.ajax({
            url: "{{ route('kelas.get_submission') }}",
            type: "GET",
            data: { student_id: studentId, sub_module_id: subModuleId, kelas_id: $('#modal-kelas-id').val() },
            success: function(response) {
                submissionContent.html(response.html);
                var $chartButton = submissionContent.find('#loadGradebookChartBtn');
                if ($chartButton.length > 0) {
                    loadChart($chartButton[0]);
                }
            },
            error: function() {
                submissionContent.html('<p class="text-danger text-center">Gagal memuat submission.</p>');
            }
        });
    });

    // 2. SAAT TOMBOL "MUAT GRAFIK" DIKLIK (Tidak Berubah)
    $('#gradingModal').on('click', '#loadGradebookChartBtn', function() {
        loadChart(this);
    });


    // 3. SAAT TOMBOL "SIMPAN NILAI" DIKLIK
    $('#saveGradeButton').on('click', function() {
        var $button = $(this);
        var form = $('#gradingForm');

        var studentId = form.find('#modal-student-id').val();
        var subModuleId = form.find('#modal-submodule-id').val();
        var newScore = form.find('#modal-score').val();
        var newFeedback = form.find('#modal-feedback').val();

        $button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({
            url: "{{ route('kelas.save_grade') }}",
            type: "POST",
            data: form.serialize(),
            success: function(response) {
                if (response.status === 'success') {

                    var cellId = '#cell-' + studentId + '-' + subModuleId;
                    var $cell = $(cellId);

                    $cell.html(response.new_cell_text);
                    $cell.removeClass('completed pending draft').addClass('graded');

                    // [PERBAIKAN DI SINI]
                    // Ganti .attr() menjadi .data() untuk memperbarui cache jQuery
                    $cell.data('current-score', newScore);
                    $cell.data('current-feedback', newFeedback);

                    updateStudentTotalScore(response.student_id);

                    gradingModal.hide();
                    Swal.fire('Sukses!', response.message, 'success');
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
                $button.prop('disabled', false).html('Simpan Nilai');
            },
            error: function(xhr) {
                var errorMsg = (xhr.status === 422) ? xhr.responseJSON.message : 'Terjadi kesalahan.';
                Swal.fire('Error!', errorMsg, 'error');
                $button.prop('disabled', false).html('Simpan Nilai');
            }
        });
    });

    // 4. FUNGSI HELPER UNTUK UPDATE TOTAL SKOR (Tidak Berubah)
    function updateStudentTotalScore(studentId) {
        var newTotalScore = 0;
        var $row = $('#total-score-' + studentId).closest('tr');

        $row.find('.grade-cell.graded').each(function() {
            var cellText = $(this).text().trim();
            var score = parseInt(cellText.split(' / ')[0]);
            if (!isNaN(score)) {
                newTotalScore += score;
            }
        });
        $('#total-score-' + studentId).html(newTotalScore + ' / ' + totalMaxPoints);
    }

});
</script>
@endpush
