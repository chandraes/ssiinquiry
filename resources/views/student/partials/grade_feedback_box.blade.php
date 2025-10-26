{{--
File: resources/views/student/partials/grade_feedback_box.blade.php
Menampilkan kotak nilai dan umpan balik jika sudah dinilai guru.
--}}

@if ($currentProgress && $currentProgress->score !== null && $subModule->type != 'learning')
    <div class="card shadow-sm mb-4 border-primary">
        <div class="card-header bg-primary bg-opacity-10 border-bottom-0">
            <h5 class="mb-0 text-primary d-flex align-items-center">
                <i class="fa fa-star fa-fw me-2"></i> Nilai & Umpan Balik Anda
            </h5>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                {{-- Kolom Skor --}}
                <div class="col-md-4 text-center">
                    <h6 class="text-muted mb-0">SKOR ANDA</h6>
                    <h1 class="display-4 fw-bold text-primary">
                        {{ $currentProgress->score }}
                        <span class="fs-5 text-muted">/ {{ $subModule->max_points }}</span>
                    </h1>
                </div>

                {{-- Kolom Feedback --}}
                <div class="col-md-8 border-start">
                    <h6 class="text-muted mb-2">UMPAN BALIK DARI GURU:</h6>
                    @if (!empty($currentProgress->feedback))
                        {{-- 'pre-wrap' akan menghargai line break (Enter) dari textarea guru --}}
                        <p class="fs-6" style="white-space: pre-wrap;">{{ $currentProgress->feedback }}</p>
                    @else
                        <p class="text-muted fst-italic">Guru tidak memberikan umpan balik tambahan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
