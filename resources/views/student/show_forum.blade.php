@extends('layouts.app')
@section('title'){{ $subModule->title }}@endsection

{{-- [CSS BARU] Menggunakan Flexbox untuk layout anti-overflow --}}
@push('css')
<style>
    /* Helper untuk avatar */
    .post-avatar {
        flex-shrink: 0;
        width: 48px;
        height: 48px;
        background-color: #6c757d;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: bold;
        font-size: 1.1rem;
    }
    .post-avatar-reply {
        width: 40px;
        height: 40px;
        font-size: 0.9rem;
    }

    /* Wrapper utama postingan */
    .post-wrapper {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    /* Body postingan (bagian kanan) */
    .post-body {
        /* [INI KUNCI ANTI-OVERFLOW] */
        flex-grow: 1;
        min-width: 0;
    }

    .post-card {
        border: 1px solid #dee2e6;
        border-radius: 8px;
    }

    .post-card.team-pro { border-left: 4px solid #198754; }
    .post-card.team-con { border-left: 4px solid #dc3545; }

    .post-header {
        background-color: #f8f9fa;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #dee2e6;
    }

    /* Konten postingan (teks, gambar) */
    .post-content {
        padding: 1rem;
        overflow-wrap: break-word;
        word-wrap: break-word;
    }

    .post-content img,
    .post-content iframe,
    .post-content video,
    .post-content table {
        max-width: 100% !important;
        height: auto;
        display: block;
    }

    .post-footer {
        padding: 0.5rem 1rem;
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }

    /* Kontainer untuk balasan */
    .post-replies {
        padding-left: 40px;
        margin-top: 1rem;
    }

    /* Form balasan yang disembunyikan */
    #hidden-reply-form-home {
        display: none;
    }
    #reply-form-wrapper {
        margin-top: 1rem;
        border-top: 2px dashed #0d6efd;
        padding-top: 1rem;
    }
</style>
@endpush

@section('content')
@include('student.partials.grade_feedback_box')
<div class="container-fluid">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="card-title">
                <i class="fa fa-comments text-danger me-2"></i>{{ $subModule->title }}
            </h2>

            @if($teamInfo)
                <div class="alert alert-{{ $teamInfo->team == 'pro' ? 'success' : 'danger' }} fs-5">
                    <i class="fa fa-users me-2"></i>
                    {{ __('admin.siswa.show_forum.your_team') }}
                    <strong>
                        {{ __('admin.siswa.show_forum.team') }}
                        {{ $teamInfo->team == 'pro'
                            ? __('admin.siswa.show_forum.pro')
                            : __('admin.siswa.show_forum.contra') }}
                    </strong>.
                </div>
            @else
                <div class="alert alert-warning">
                    {{ __('admin.siswa.show_forum.unlisted') }}.
                </div>
            @endif

            <hr>

            <h4>{{ __('admin.siswa.show_forum.debate_topic') }}:</h4>
            <p class="lead">
                {{ $subModule->getTranslation('debate_topic', 'id') ?? __('admin.siswa.show_forum.no_topic') }}
            </p>

            <h4>{{ __('admin.siswa.show_forum.debate_rule') }}:</h4>
            <div class="rich-text-content p-2">
                {!! $subModule->getTranslation('debate_rules', 'id')
                    ?? '<p>' . __('admin.siswa.show_forum.no_rule') . '.</p>' !!}
            </div>
        </div>
    </div>


    @if($teamInfo)
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0">{{__('admin.siswa.show_forum.add_post')}}</h5>
        </div>
        <form action="{{ route('student.forum.store', [$kelas->id, $subModule->id]) }}" method="POST" id="main-post-form">
            @csrf
            <div class="card-body">
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
                @if ($errors->hasBag('mainPost'))
                    <div class="alert alert-danger">
                        {{__('admin.siswa.show_forum.send_failed')}}: {{ $errors->mainPost->first('content') }}
                    </div>
                @endif

                <div class="mb-3">
                    <label for="forum-editor" class="form-label">{{__('admin.siswa.show_forum.your_argumen')}}:</label>
                    <textarea name="content" id="forum-editor" rows="8">{{ old('content') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{__('admin.siswa.show_forum.attach')}}:</label>
                    @forelse($mySubmissions as $submission)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="evidence_ids[]"
                               value="{{ $submission->id }}" id="main-evidence-{{ $submission->id }}">
                        <label class="form-check-label" for="main-evidence-{{ $submission->id }}">
                            {{ $submission->original_filename }}
                            <small class="text-muted">({{__('admin.siswa.show_forum.from_slot')}}: {{ $submission->slot->label }})</small>
                        </label>
                    </div>
                    @empty
                    <p class="text-muted small">{{__('admin.siswa.show_forum.not_upload')}}.</p>
                    @endforelse
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary btn-lg">{{__('admin.siswa.show_forum.send_new_post')}}</button>
            </div>
        </form>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">{{__('admin.siswa.show_forum.debate_arena')}}</h5>
        </div>
        <div class="card-body">
            @forelse($posts as $post)
                {{-- Kirim data postingan ke partial view --}}
                @include('student.partials.forum_post', ['post' => $post, 'is_reply' => false, 'mySubmissions' => $mySubmissions])
            @empty
                <div class="alert alert-light text-center">
                    {{__('admin.siswa.show_forum.no_post')}}
                </div>
            @endforelse
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-body text-center">
            @if($currentProgress && $currentProgress->completed_at)
                <div class="alert alert-success mb-0">
                    <i class="fa fa-check-circle me-2"></i>
                    {{__('admin.siswa.show_forum.complete_forum')}} {{ $currentProgress->completed_at->format('d M Y, H:i') }}.
                </div>
            @else
                <p class="lead">{{__('admin.siswa.show_forum.debate_lead')}}.</p>
                <form action="{{ route('student.submodule.complete', [$kelas->id, $subModule->id]) }}" method="POST" id="complete-forum-form">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg">
                        {{__('admin.siswa.show_forum.complete_button')}}<i class="fa fa-arrow-right ms-2"></i>
                    </button>
                </form>
            @endif
        </div>
    </div>

</div>

{{-- Template Form Balasan (Disembunyikan) --}}
<div id="hidden-reply-form-home">
    <div id="reply-form-wrapper">
        <form action="{{ route('student.forum.store', [$kelas->id, $subModule->id]) }}" method="POST" class="reply-post-form">
            @csrf
            <input type="hidden" name="parent_post_id" class="parent-post-id-input" value="">

            <div class="mb-3">
                <label for="reply-editor-{{ $subModule->id }}" class="form-label">{{__('admin.siswa.show_forum.your_reply')}}:</label>
                <textarea name="content" id="reply-editor-{{ $subModule->id }}" class="reply-editor" rows="6"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">{{__('admin.siswa.show_forum.attach_post')}}:</label>
                @forelse($mySubmissions as $submission)
                <div class="form-check form-check-sm">
                    <input class="form-check-input" type="checkbox" name="evidence_ids[]"
                           value="{{ $submission->id }}" id="reply-evidence-{{ $submission->id }}">
                    <label class="form-check-label" for="reply-evidence-{{ $submission->id }}">
                        <small>{{ $submission->original_filename }}</small>
                    </label>
                </div>
                @empty
                <p class="text-muted small">{{__('admin.siswa.show_forum.no_file')}}.</p>
                @endforelse
            </div>

            @if ($errors->hasBag('replyPost'))
                <div class="alert alert-danger small p-2">
                    {{__('admin.siswa.show_forum.reply_failed')}}: {{ $errors->replyPost->first('content') }}
                </div>
            @endif

            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-sm btn-secondary cancel-reply-btn">{{__('admin.button.cancel')}}</button>
                <button type="submit" class="btn btn-sm btn-primary">{{__('admin.siswa.show_forum.send')}}</button>
            </div>
        </form>
    </div>
</div>
<div class="card shadow-sm mb-4">
    <div class="card-footer text-center">
        <a href="{{ route('student.class.show', $kelas->id) }}" class="btn btn-outline-secondary">
            <i class="fa fa-arrow-left me-2"></i> {{__('admin.siswa.back_to_curriculum')}}
        </a>
    </div>
</div>
@endsection
@push('js')
<script>
jQuery(document).ready(function($) {

    // --- Bagian 1: Setup Form Balasan ---
    var $replyFormWrapper = $('#reply-form-wrapper');
    var $replyFormHome = $('#hidden-reply-form-home');
    var $replyForm = $replyFormWrapper.find('form');
    var $replyParentIdInput = $replyFormWrapper.find('.parent-post-id-input');
    var replyEditorID = 'reply-editor-{{ $subModule->id }}';

    function initTinyMCE(selector, callback) {
        if (tinymce.get(selector)) { tinymce.get(selector).remove(); }
        tinymce.init({
            selector: selector,
            plugins: 'lists link image media autoresize',
            toolbar: 'undo redo | bold italic | bullist numlist | link',
            height: 200, menubar: false, autoresize_bottom_margin: 15, license_key: 'gpl',
            setup: function(editor) {
                editor.on('init', function() {
                    if (callback) { callback(editor); }
                });
            }
        });
    }

    initTinyMCE('#forum-editor', null);

    // --- Bagian 2: Event Listeners Form (Balas, Batal, Submit) ---
    $(document).on('click', '.reply-btn', function() {
        var postId = $(this).data('post-id');
        var $targetContainer = $('#reply-container-' + postId);
        $replyFormWrapper.detach().appendTo($targetContainer);
        $replyParentIdInput.val(postId);
        $replyFormWrapper.slideDown(function() {
            initTinyMCE('#' + replyEditorID, function(editor) { editor.focus(); });
        });
    });

    $(document).on('click', '.cancel-reply-btn', function() {
        if (tinymce.get(replyEditorID)) { tinymce.get(replyEditorID).remove(); }
        $replyFormWrapper.slideUp(function() {
            $replyFormWrapper.detach().appendTo($replyFormHome);
            $replyParentIdInput.val('');
            $replyForm[0].reset();
        });
    });

    $(document).on('submit', 'form#main-post-form, form.reply-post-form', function(e) {
        e.preventDefault();
        var form = this, isReply = $(form).hasClass('reply-post-form');
        var editorInstance = isReply ? tinymce.get(replyEditorID) : tinymce.get('forum-editor');
        if (editorInstance) { editorInstance.save(); } else { return; }

        Swal.fire({
            title: isReply ? '{{__("admin.siswa.show_forum.send_reply")}}?' : '{{__("admin.siswa.show_forum.send_post")}}?',
            text: '{{__("admin.siswa.show_forum.send_text")}}.',
            icon: 'question', showCancelButton: true,
            confirmButtonText: '{{__("admin.siswa.show_forum.send_confirmation")}}', cancelButtonText: '{{__("admin.button.cancel")}}',
        }).then((result) => { if (result.isConfirmed) { form.submit(); } });
    });

    $(document).on('submit', '#complete-forum-form', function(e) {
        e.preventDefault();
        var form = this;
        Swal.fire({
            title: '{{__("admin.siswa.show_forum.debate_title")}}?', text: '{{__("admin.siswa.show_forum.dabate_text")}}.',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: '{{__("admin.siswa.show_forum.debate_confirmation")}}', cancelButtonText: '{{__("admin.button.cancel")}}.',
        }).then((result) => { if (result.isConfirmed) { form.submit(); } });
    });


    // --- [BARU] Bagian 3: Logika Grafik Bukti ---

    var evidenceCharts = {}; // Objek untuk menyimpan chart bukti

    $(document).on('click', '.view-evidence-btn', function() {
        var $button = $(this);
        var canvasId = $button.data('canvas-id');
        var containerId = '#evidence-chart-container-' + canvasId.split('-').pop();
        var $container = $(containerId);
        var evidenceJson = $button.data('evidence-json');

        // Hancurkan chart lama jika ada
        if (evidenceCharts[canvasId]) {
            evidenceCharts[canvasId].destroy();
        }

        // Tampilkan/Sembunyikan
        $container.slideToggle();
        if (!$container.is(":visible")) {
            $button.html('<i class="fa fa-bar-chart me-2"></i> Tampilkan Grafik Bukti');
            return;
        }

        if (!evidenceJson || evidenceJson.length === 0) {
            alert('{{__("admin.siswa.show_forum.attach_alert")}}.');
            return;
        }

        // Tentukan tipe chart (paksa 'line' untuk perbandingan)
        var chartType = 'line';

        var ctx = document.getElementById(canvasId).getContext('2d');
        $button.html('<i class="fa fa-spinner fa-spin me-2"></i> {{__("admin.siswa.show_forum.load_chart")}}');

        var fetchPromises = [];
        evidenceJson.forEach(function(evidence) {
            fetchPromises.push(
                fetchAndParseCsv(evidence.url, evidence.label, ";") // Asumsi delimiter ;
            );
        });

        // Jalankan semua
        Promise.all(fetchPromises)
            .then(allParsedData => {
                // Panggil helper 'drawComparisonChart' (didefinisikan di bawah)
                // Kita berikan 'ctx' agar ia tahu di mana harus menggambar
                evidenceCharts[canvasId] = drawComparisonChart(ctx, allParsedData, chartType);
                $button.html('<i class="fa fa-bar-chart me-2"></i> {{__("admin.siswa.show_forum.show_attach")}}');
            })
            .catch(error => {
                alert('{{__("admin.siswa.show_forum.error")}}: ' + error.message);
                $button.html('<i class="fa fa-bar-chart me-2"></i> {{__("admin.siswa.show_forum.show_attach")}}');
            });
    });

    // --- [BARU] Bagian 4: Fungsi Helper (Disalin dari Practicum) ---

    /**
     * Helper: Mengambil URL, mem-parsing CSV
     */
    function fetchAndParseCsv(url, label, delimiter) {
        return new Promise((resolve, reject) => {
            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('{{__("admin.siswa.show_forum.file")}} ' + label + ' {{__("admin.siswa.show_forum.load_failed")}}.');
                    return response.text();
                })
                .then(csvText => {
                    Papa.parse(csvText, {
                        skipEmptyLines: true,
                        delimiter: delimiter,
                        complete: function(results) {
                            resolve({ label: label, csvData: results.data });
                        },
                        error: (err) => reject(new Error('{{__("admin.siswa.show_forum.parse_failed")}} ' + label + ': ' + err.message))
                    });
                })
                .catch(err => reject(err));
        });
    }

    /**
     * Helper: Menggambar BANYAK dataset di SATU chart
     * (Versi generik yang menerima 'ctx')
     */
    function drawComparisonChart(ctx, allParsedData, chartType) {
        var colorPalette = [
            'rgb(75, 192, 192)', 'rgb(255, 99, 132)', 'rgb(54, 162, 235)',
            'rgb(255, 206, 86)', 'rgb(153, 102, 255)', 'rgb(255, 159, 64)'
        ];

        var xLabel = (allParsedData[0].csvData.length > 0) ? allParsedData[0].csvData[0][0] : 'Kolom 1';
        var yLabel = (allParsedData[0].csvData.length > 0) ? allParsedData[0].csvData[0][1] : 'Kolom 2';

        var datasets = [];
        allParsedData.forEach((parsedFile, index) => {
            var dataRows = parsedFile.csvData.slice(1); // Lewati header
            var xyData = dataRows.map(row => ({
                x: parseFloat(row[0]), // Kolom 0
                y: parseFloat(row[1])  // Kolom 1
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

        // Gambar Chart dan return instance-nya
        return new Chart(ctx, {
            type: 'line',
            data: { datasets: datasets },
            options: {
                animation: false,
                scales: {
                    x: { type: 'linear', title: { display: true, text: xLabel } },
                    y: { title: { display: true, text: yLabel } }
                }
            }
        });
    }

}); // <-- Akhir dari jQuery(document).ready()
</script>
@endpush
