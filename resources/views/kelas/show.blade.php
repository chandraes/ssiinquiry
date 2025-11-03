@extends('layouts.app')

@section('title')
    Detail Kelas: {{ $kelas->nama_kelas }}
@endsection

@push('css')
<style>
    /* --- Gradebook Table Base Styles --- */
    .gradebook-table th,
    .gradebook-table td {
        vertical-align: middle;
        text-align: center;
        min-width: 150px; /* Slightly reduced default min-width */
        padding: 0.5rem;
        white-space: nowrap; /* Prevent headers wrapping too soon */
    }
    .gradebook-table th:first-child, /* Nama Siswa Header */
    .gradebook-table td:first-child { /* Nama Siswa Cell */
        text-align: left;
        min-width: 180px; /* Slightly reduced name column width */
        white-space: normal; /* Allow names to wrap if needed */
        /* --- Desktop Sticky --- */
        position: sticky;
        left: 0;
        background-color: #f8f9fa; /* Lighter background for sticky */
        z-index: 1;
        /* Add subtle shadow to indicate scroll */
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    }
    .gradebook-table thead th { /* All Header Cells */
        position: sticky;
        top: 0;
        background-color: #e9ecef;
        z-index: 2;
    }
    .gradebook-table thead th:first-child {
        z-index: 3; /* Ensure Nama Siswa header is above other sticky headers */
    }

    /* --- Grade Cell Styles (No Change Needed) --- */
    .grade-cell { display: block; padding: 0.75rem 0.5rem; text-decoration: none; color: #212529; border-radius: 4px; background-color: #fff; border: 1px dashed #ced4da; min-height: 50px; }
    .grade-cell:hover { background-color: #f1f3f5; border-color: #0d6efd; cursor: pointer; }
    .grade-cell.graded { background-color: #e6f7ff; border-color: #b3e0ff; border-style: solid; font-weight: bold; }
    .grade-cell.completed { background-color: #f0fff4; border-style: solid; border-color: #28a745; }

    /* --- Modal Forum/Highlight Styles (No Change Needed) --- */
    .post-highlight { background-color: #fff3cd !important; border: 1px solid #ffeeba; box-shadow: 0 0 10px rgba(255, 193, 7, 0.3); }
    .forum-column { padding: 8px; border: 1px solid #eee; border-radius: 5px; height: 400px; overflow-y: auto; background: #fdfdfd; }
    .forum-post { border: 1px solid #ddd; border-radius: 5px; padding: 10px; margin-bottom: 10px; background: #fff; overflow-wrap: break-word; } /* Added overflow-wrap */
    .forum-reply { border-top: 1px dashed #ccc; padding: 8px 8px 8px 15px; margin-top: 8px; margin-left: 10px; background: #f9f9f9; overflow-wrap: break-word; } /* Added overflow-wrap */
    .post-author { font-weight: bold; color: #0d6efd; }
    .reply-author { font-weight: bold; color: #555; }
    /* Ensure rich text content inside modal wraps */
    #modal-submission-content .rich-text-content {
         overflow-wrap: break-word;
         word-wrap: break-word; /* legacy */
         word-break: break-word;
    }

    /* --- [BARU] Mobile Responsiveness --- */
    @media (max-width: 767.98px) { /* Target devices smaller than md breakpoint */
        .gradebook-table th,
        .gradebook-table td {
            min-width: 130px; /* Further reduce min-width for columns */
        }
        .gradebook-table th:first-child,
        .gradebook-table td:first-child {
            position: static; /* Remove sticky positioning for name column */
            left: auto;
            min-width: 150px;
            box-shadow: none; /* Remove shadow */
            background-color: #f8f9fa; /* Keep slight background distinction */
        }
        .gradebook-table thead th {
             /* Keep header sticky */
            z-index: 2;
        }
        .gradebook-table thead th:first-child {
             z-index: 2; /* No need for higher z-index if not sticky left */
             background-color: #e9ecef; /* Match other header cells */
        }

        /* Adjust Kode Gabung Card layout */
        .join-code-container {
            flex-direction: column; /* Stack vertically */
            align-items: flex-start !important; /* Align items to start */
        }
        .join-code-display {
             margin-bottom: 0.5rem; /* Add space below code */
             width: 100%; /* Make code span full width */
             text-align: center;
        }
        #joinCode {
            font-size: 1.25rem !important; /* Slightly smaller font size */
        }
        #copyCodeBtn {
             width: 100%; /* Make button full width */
        }
    }
</style>
@endpush


@section('content')
<div class="container-fluid">
    <div class="col-md-12 mb-5">
        <a href="{{ route('kelas') }}" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left me-1"></i> {{ __('admin.button.back') }}
        </a>
    </div>

    {{-- Info Kelas Card --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="card-title">{{ $kelas->nama_kelas }}</h2>
            <p class="text-muted mb-1">{{__('admin.kelas.show.module')}}: <strong>{{ $kelas->modul->judul }}</strong></p>
            <p class="text-muted mb-1">{{__('admin.kelas.show.teacher')}}: <strong>{{ $kelas->guru?->name ?? 'Belum Ditugaskan' }}</strong></p>
            <p class="text-muted mb-0">{{__('admin.kelas.show.num_participants')}}: <strong>{{ $kelas->peserta_count }} Siswa</strong></p>
        </div>
    </div>

    {{-- Navigasi Tab --}}
    <ul class="nav nav-tabs nav-fill mb-0" id="myTab" role="tablist"> {{-- Removed mb-0 if you want card connected --}}
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary-pane" type="button" role="tab" aria-controls="summary-pane" aria-selected="true">
                <i class="fa fa-th-large me-2"></i> {{__('admin.kelas.show.resume_management')}}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="gradebook-tab" data-bs-toggle="tab" data-bs-target="#gradebook-pane" type="button" role="tab" aria-controls="gradebook-pane" aria-selected="false">
                <i class="fa fa-check-square-o me-2"></i> {{__('admin.kelas.show.gradebook')}} (Gradebook)
            </button>
        </li>
    </ul>

    {{-- Konten Tab --}}
    <div class="tab-content" id="myTabContent">

        {{-- TAB 1: RINGKASAN --}}
        <div class="tab-pane fade show active" id="summary-pane" role="tabpanel" aria-labelledby="summary-tab" tabindex="0">
            {{-- Make card connect to tabs --}}
            <div class="card shadow-sm border-top-0 rounded-0 rounded-bottom">
                <div class="card-body p-4">

                    {{-- Kartu Kode Gabung Kelas --}}
                    <div class="card shadow-sm mb-4 border-primary">
                        <div class="card-body">
                            {{-- [PERBAIKAN] Added join-code-container class --}}
                            <div class="d-flex justify-content-between align-items-center join-code-container">
                                <div>
                                    <h5 class="card-title mb-0 text-primary">{{__('admin.kelas.show.join_code')}}</h5>
                                    <p class="card-text text-muted mb-0">{{__('admin.kelas.show.instruction_join_code')}}.</p>
                                </div>
                                {{-- [PERBAIKAN] Added join-code-display class --}}
                                <div class="text-center join-code-display">
                                    <span id="joinCode" class="badge bg-primary-light text-primary p-3 fs-4 me-sm-2" style="font-family: 'Courier New', monospace; letter-spacing: 2px;">
                                        {{ $kelas->kode_join }}
                                    </span>
                                    <button class="btn btn-secondary btn-sm" id="copyCodeBtn" title="Salin Kode">
                                        <i class="fa fa-copy"></i> {{__('admin.kelas.show.copy')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kartu Navigasi Manajemen --}}
                    <div class="row">
                        {{-- Manajemen Peserta --}}
                        <div class="col-sm-6 col-lg-4 mb-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center d-flex flex-column justify-content-between">
                                    <div>
                                        <i class="fa fa-users fa-3x text-primary mb-3"></i>
                                        <h5 class="card-title">{{__('admin.kelas.show.manage_participants')}}</h5>
                                        <p class="card-text text-muted small">{{__('admin.kelas.show.add_delete_participants')}}.</p>
                                    </div>
                                    <a href="{{ route('kelas.peserta', $kelas->id) }}" class="btn btn-primary mt-3">
                                        {{__('admin.kelas.show.button_manage_participant')}}
                                    </a>
                                </div>
                            </div>
                        </div>
                        {{-- Manajemen Forum --}}
                        <div class="col-sm-6 col-lg-4 mb-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center d-flex flex-column justify-content-between">
                                    <div>
                                        <i class="fa fa-comments fa-3x text-success mb-3"></i>
                                        <h5 class="card-title">{{__('admin.kelas.show.manage_forum')}}</h5>
                                        <p class="card-text text-muted small">{{__('admin.kelas.show.setup_teams')}}.</p>
                                    </div>
                                    <a href="{{ route('kelas.forums', $kelas->id) }}" class="btn btn-success mt-3">
                                        {{__('admin.kelas.show.button_manage_forum')}}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 mb-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center d-flex flex-column justify-content-between">
                                    <div>
                                        <i class="fa fa-eye fa-3x text-info mb-3"></i>
                                        <h5 class="card-title">Tinjau Forum Debat</h5>
                                        <p class="card-text text-muted small">Lihat progres debat siswa.</p>
                                    </div>
                                    {{-- Kita akan membuat route 'kelas.forum.viewer' di langkah berikutnya --}}
                                    <a href="{{ route('kelas.forum.viewer', $kelas->id) }}" class="btn btn-info mt-3">
                                        Tinjau Forum
                                    </a>
                                </div>
                            </div>
                        </div>
                        {{-- Add other management cards here if needed --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 2: GRADEBOOK --}}
        <div class="tab-pane fade" id="gradebook-pane" role="tabpanel" aria-labelledby="gradebook-tab" tabindex="0">
            <div class="card shadow-sm border-top-0 rounded-0 rounded-bottom">
                 {{-- Add padding for better spacing around table --}}
                <div class="card-body p-0 p-md-3">
                    <div class="table-responsive">
                        @php $totalMaxPoints = $subModules->sum('max_points'); @endphp
                        <table class="table table-bordered table-hover gradebook-table mb-0"> {{-- Removed mb-0 --}}
                            <thead class="table-light">
                                <tr>
                                    <th>{{__('admin.kelas.show.participant_table_name')}}</th>
                                    @foreach($subModules as $subModule)
                                        <th>
                                            {{ $subModule->title }}
                                            <span class="d-block small text-muted">
                                                {{ $subModule->max_points }} {{__('admin.kelas.show.participant_table_point')}}
                                            </span>
                                        </th>
                                    @endforeach
                                    <th>
                                        {{__('admin.kelas.show.participant_table_score')}}
                                        <span class="d-block small text-muted">
                                            {{ $totalMaxPoints }} {{__('admin.kelas.show.participant_table_point')}}
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    @php $studentTotalScore = 0; @endphp
                                    <tr>
                                        <td>{{ $student->name }}</td>
                                        @foreach($subModules as $subModule)
                                            @php
                                                $key = $student->id . '_' . $subModule->id;
                                                $progress = $allProgress->get($key);
                                                $cellClass = ''; $cellText = "{{__('admin.kelas.show.participant_table_grade')}}";
                                                if ($progress) {
                                                    if ($progress->score !== null) {
                                                        $cellClass = 'graded';
                                                        $cellText = $progress->score . ' / ' . $subModule->max_points;
                                                        $studentTotalScore += $progress->score;
                                                    } elseif ($progress->completed_at) {
                                                        $cellClass = 'completed';
                                                        $cellText = '<i class="fa fa-check-circle text-success me-1"></i>'. __('admin.kelas.show.participant_table_finish');
                                                    } else { $cellClass = 'draft'; $cellText = 'Draf'; }
                                                } else { $cellClass = 'pending'; $cellText = '-'; }
                                            @endphp
                                            <td>
                                                <a href="#"
                                                   class="grade-cell {{ $cellClass }}"
                                                   id="cell-{{ $student->id }}-{{ $subModule->id }}"
                                                   data-bs-toggle="modal" data-bs-target="#gradingModal"
                                                   data-student-id="{{ $student->id }}" data-student-name="{{ $student->name }}"
                                                   data-submodule-id="{{ $subModule->id }}" data-submodule-title="{{ $subModule->title }}"
                                                   data-submodule-type="{{ $subModule->type }}" data-max-points="{{ $subModule->max_points }}"
                                                   data-current-score="{{ $progress->score ?? '' }}" data-current-feedback="{{ $progress->feedback ?? '' }}">
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
                                        <td colspan="{{ $subModules->count() + 2 }}" class="text-center p-4 text-muted">
                                            {{__('admin.kelas.show.no_participants')}}.
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

{{-- Modal Penilaian (No Structural Changes Needed) --}}
<div class="modal fade" id="gradingModal" tabindex="-1" aria-labelledby="gradingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gradingModalLabel">Beri Nilai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Siswa: <strong id="modal-student-name"></strong></h6>
                <h6>Tugas: <strong id="modal-submodule-title"></strong></h6>
                <hr>
                <div id="modal-submission-content" class="mb-3 p-3 border" style="background-color: #f8f9fa;">
                    {{-- Content loaded via AJAX --}}
                </div>
                <form id="gradingForm">
                    @csrf
                    <input type="hidden" id="modal-student-id" name="student_id">
                    <input type="hidden" id="modal-submodule-id" name="sub_module_id">
                    <input type="hidden" id="modal-kelas-id" name="kelas_id" value="{{ $kelas->id }}">
                    <div class="mb-3">
                        <label for="modal-score" class="form-label">
                            Skor (Maks: <span id="modal-max-points"></span>)
                        </label>
                        <input type="number" class="form-control" id="modal-score" name="score" min="0">
                    </div>
                    <div class="mb-3">
                        <label for="modal-feedback" class="form-label">Umpan Balik</label>
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
    // --- Existing JS ---
    var gradingModal = new bootstrap.Modal(document.getElementById('gradingModal'));
    var modalEl = document.getElementById('gradingModal');
    // Calculate totalMaxPoints directly in JS if needed, or pass from controller if static
    var totalMaxPoints = {{ $subModules->sum('max_points') }};
    var myChart = null;

    $('#copyCodeBtn').on('click', function() {
        var code = $('#joinCode').text().trim();
        navigator.clipboard.writeText(code).then(function() {
            Swal.fire({ icon: 'success', title: 'Kode Disalin!', text: '"' + code + '" disalin.', timer: 2000, showConfirmButton: false });
        }, function() {
            Swal.fire({ icon: 'error', title: 'Oops...', text: 'Gagal menyalin kode.' });
        });
    });

    // --- Chart & Modal Functions (No changes needed based on UX request) ---
    function fetchAndParseCsv(url, label, delimiter) { /* ... */
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
    function drawChartFromParsedData(ctx, allParsedData) { /* ... */
        var colorPalette = ['rgb(75, 192, 192)', 'rgb(255, 99, 132)', 'rgb(54, 162, 235)', 'rgb(255, 206, 86)', 'rgb(153, 102, 255)', 'rgb(255, 159, 64)'];
        var xLabel = (allParsedData.length > 0 && allParsedData[0].csvData.length > 0) ? allParsedData[0].csvData[0][0] : 'Kolom 1';
        var yLabel = (allParsedData.length > 0 && allParsedData[0].csvData.length > 0) ? allParsedData[0].csvData[0][1] : 'Kolom 2';
        var datasets = [];

        allParsedData.forEach((parsedFile, index) => {
            var dataRows = parsedFile.csvData.slice(1);
            var xyData = dataRows.map(row => ({ x: parseFloat(row[0]), y: parseFloat(row[1]) }));
            var color = colorPalette[index % colorPalette.length];
            datasets.push({ label: parsedFile.label, data: xyData, borderColor: color, backgroundColor: color + '33', tension: 0.1, pointRadius: 0 });
        });

        if (myChart) { myChart.destroy(); }

        myChart = new Chart(ctx, {
            type: 'line', data: { datasets: datasets },
            options: { animation: false, responsive: true, maintainAspectRatio: false,
                scales: { x: { type: 'linear', title: { display: true, text: xLabel } }, y: { title: { display: true, text: yLabel } } },
                plugins: { tooltip: { mode: 'index', intersect: false }, legend: { position: 'bottom' } } }
        });
    }
    function loadChart(button) { /* ... */
        var $button = $(button);
        var datasets = $button.data('json');
        var ctx = document.getElementById('gradebookChartCanvas')?.getContext('2d'); // Added null check

        if (ctx && datasets && datasets.length > 0) {
            $button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memuat...');
            var fetchPromises = datasets.map(dataset => fetchAndParseCsv(dataset.url, dataset.label, ";"));

            Promise.all(fetchPromises)
                .then(allParsedData => {
                    drawChartFromParsedData(ctx, allParsedData);
                    $button.prop('disabled', false).html('<i class="fa fa-sync-alt"></i> Muat Ulang');
                })
                .catch(error => {
                    console.error('Gagal menggambar chart:', error);
                    $button.prop('disabled', false).html('<i class="fa fa-exclamation-triangle"></i> Coba Lagi');
                });
        } else if (!ctx) {
             console.error('Canvas element #gradebookChartCanvas not found.');
        }
    }

    // --- Modal Event Listeners (No changes needed based on UX request) ---
    modalEl.addEventListener('show.bs.modal', function (event) { /* ... */
        var button = $(event.relatedTarget);
        var studentId = button.data('student-id'), studentName = button.data('student-name');
        var subModuleId = button.data('submodule-id'), subModuleTitle = button.data('submodule-title'), subModuleType = button.data('submodule-type');
        var maxPoints = button.data('max-points'), currentScore = button.data('current-score'), currentFeedback = button.data('current-feedback');
        var modal = $(this);

        if (myChart) { myChart.destroy(); myChart = null; } // Ensure chart is destroyed

        modal.find('#modal-student-name').text(studentName);
        modal.find('#modal-submodule-title').text(subModuleTitle + ' (' + subModuleType + ')');
        modal.find('#modal-max-points').text(maxPoints);
        modal.find('#modal-student-id').val(studentId);
        modal.find('#modal-submodule-id').val(subModuleId);
        modal.find('#modal-score').val(currentScore).attr('max', maxPoints);
        modal.find('#modal-feedback').val(currentFeedback);

        var submissionContent = modal.find('#modal-submission-content').html('<p class="text-muted text-center"><i class="fa fa-spinner fa-spin"></i> Memuat...</p>');

        $.ajax({
            url: "{{ route('kelas.get_submission') }}", type: "GET",
            data: { student_id: studentId, sub_module_id: subModuleId, kelas_id: $('#modal-kelas-id').val() },
            success: function(response) {
                submissionContent.html(response.html);
                var $chartButton = submissionContent.find('#loadGradebookChartBtn');
                if ($chartButton.length > 0) { loadChart($chartButton[0]); }
            },
            error: function() { submissionContent.html('<p class="text-danger text-center">Gagal memuat.</p>'); }
        });
    });
    $('#gradingModal').on('click', '#loadGradebookChartBtn', function() { loadChart(this); });
    $('#saveGradeButton').on('click', function() { /* ... */
        var $button = $(this); var form = $('#gradingForm');
        var studentId = form.find('#modal-student-id').val(), subModuleId = form.find('#modal-submodule-id').val();
        var newScore = form.find('#modal-score').val(), newFeedback = form.find('#modal-feedback').val();

        $button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({
            url: "{{ route('kelas.save_grade') }}", type: "POST", data: form.serialize(),
            success: function(response) {
                if (response.status === 'success') {
                    var cellId = '#cell-' + studentId + '-' + subModuleId; var $cell = $(cellId);
                    $cell.html(response.new_cell_text).removeClass('completed pending draft').addClass('graded');
                    $cell.data('current-score', newScore); $cell.data('current-feedback', newFeedback);
                    updateStudentTotalScore(studentId);
                    gradingModal.hide();
                    Swal.fire('Sukses!', response.message, 'success');
                } else { Swal.fire('Error!', response.message, 'error'); }
                $button.prop('disabled', false).html('Simpan Nilai');
            },
            error: function(xhr) {
                var errorMsg = (xhr.status === 422) ? (xhr.responseJSON?.message ?? 'Input tidak valid.') : 'Terjadi kesalahan.';
                Swal.fire('Error!', errorMsg, 'error');
                $button.prop('disabled', false).html('Simpan Nilai');
            }
        });
    });
    function updateStudentTotalScore(studentId) { /* ... */
         var newTotalScore = 0;
        var $row = $('#total-score-' + studentId).closest('tr');

        $row.find('.grade-cell.graded').each(function() {
            var cellText = $(this).text().trim();
            // Handle potential non-numeric text before split
            if (cellText.includes('/')) {
                 var scorePart = cellText.split(' / ')[0];
                 var score = parseInt(scorePart);
                 if (!isNaN(score)) { newTotalScore += score; }
            } else {
                 var score = parseInt(cellText); // Try parsing directly if no '/'
                 if (!isNaN(score)) { newTotalScore += score; }
            }
        });
         // Make sure totalMaxPoints is accessible or recalculated if needed
         $('#total-score-' + studentId).html(newTotalScore + ' / ' + totalMaxPoints);
    }

});
</script>
@endpush
