@extends('layouts.app')
@section('title'){{__('admin.review_forum.title')}} : {{ $kelas->nama_kelas }}@endsection

{{-- Salin CSS dari student/show_forum.blade.php --}}
@push('css')
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

    /* * [PENTING] Memastikan SEMUA konten tidak overflow.
     * Termasuk tabel, video, dan (yang sering lupa) <pre> tag untuk kode.
    */
    .post-content img,
    .post-content iframe,
    .post-content video,
    .post-content table,
    .post-content pre {
        max-width: 100% !important;
        height: auto;
        display: block;
    }
    /* Khusus untuk <pre> dan <table>, butuh overflow horizontal */
    .post-content pre,
    .post-content table {
        overflow-x: auto;
    }

    .post-footer {
        padding: 0.5rem 1rem;
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }

    /* * [PERBAIKAN DESKTOP]
     * Kontainer untuk balasan (Tampilan "Thread Line" Profesional)
    */
    .post-replies {
        /* * Pindahkan indentasi dari avatar (48px / 2 = 24px)
         * agar garis ada di tengah avatar.
        */
        margin-left: 24px;
        padding-left: 24px; /* (48px - 24px) */
        margin-top: 1rem;
        border-left: 2px solid #e9ecef; /* Garis thread */
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


    /* ================================================= */
    /* === 🚀 SOLUSI RESPONSIVE MOBILE (ANTI-OVERFLOW) 🚀 === */
    /* ================================================= */
    @media (max-width: 576px) {

        /* 1. Perkecil avatar dan gap untuk hemat ruang */
        .post-wrapper {
            gap: 0.75rem; /* (12px) */
        }
        .post-avatar {
            width: 36px;
            height: 36px;
            font-size: 0.9rem;
        }
        .post-avatar-reply {
            width: 36px; /* Samakan saja */
            height: 36px;
            font-size: 0.9rem;
        }

        /* * 2. [INI SOLUSI UTAMA ANDA]
         * Kita "ratakan" (flatten) tampilan balasan di mobile.
         * Hapus semua indentasi kumulatif.
        */
        .post-replies {
            margin-left: 0;
            padding-left: 0;
            margin-top: 0.75rem;
            border-left: none; /* Hapus garis thread, ganti style */
        }

        /* * 3. Ganti visual balasan
         * Sebagai ganti indentasi, kita beri garis pemisah tipis
         * di atas setiap balasan baru.
        */
        .post-replies > .post-wrapper {
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            /* Garis pemisah tipis ini jauh lebih hemat ruang */
            border-top: 1px solid #e9ecef;
        }

        /* 4. Perkecil padding di dalam card */
        .post-header,
        .post-content,
        .evidence-list {
            padding: 0.75rem;
        }
        .post-footer {
            padding: 0.5rem 0.75rem;
        }

        /* 5. Pastikan header tidak rusak */
        .post-header .d-flex {
            flex-wrap: wrap; /* Izinkan timestamp turun ke bawah */
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    {{-- Header Halaman Admin --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="card-title">{{__('admin.review_forum.header')}}</h2>
            <p class="text-muted mb-0">{{__('admin.forum.class')}} : <strong>{{ $kelas->nama_kelas }}</strong></p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-4">

            {{-- Dropdown Pilihan Sub-Modul (Dengan Tombol) --}}
            <form action="{{ route('kelas.forum.viewer', $kelas->id) }}" method="GET">
                <div class="input-group mb-4">
                    <label class="input-group-text" for="view_submodule">{{__('admin.review_forum.choose_forum')}}:</label>
                    <select class="form-select" name="view_submodule" id="view_submodule">
                        <option value="">-- {{__('admin.review_forum.choose_forum')}} --</option>
                        @foreach($forumSubModules as $subModule)
                            <option value="{{ $subModule->id }}"
                                    {{ $selectedSubModule && $selectedSubModule->id == $subModule->id ? 'selected' : '' }}>
                                {{ $subModule->title }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-eye me-1"></i> {{__('admin.button.show')}}
                    </button>
                </div>
            </form>

            @if(!$selectedSubModule)
                <div class="alert alert-info text-center">
                    {{__('admin.review_forum.instruction')}}.
                </div>
            @else
                {{-- [PERBAIKAN] Tampilkan Tampilan Satu Kolom --}}
                <div class="forum-viewer-container">
                    <div class="forum-column">
                        @forelse($allPosts as $post)
                            @include('student.partials.forum_post', [
                                'post' => $post,
                                'is_reply' => false,
                                'mySubmissions' => $mySubmissions,
                                'isAdminView' => $isAdminView
                            ])
                        @empty
                            <p class="text-muted text-center">{{__('admin.review_forum.no_post')}}.</p>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>
        <div class="card-footer">
            <div class="col-md-12">
                <a href="{{ route('kelas.show', $kelas->id) }}" class="btn btn-secondary button-lg">
                    <i class="fa fa-arrow-left me-1"></i> {{ __('admin.button.back_to') }} {{ __('admin.kelas.show.header') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
jQuery(document).ready(function($) {

    // --- [DARI SISWA] Bagian 3: Logika Grafik Bukti ---

    var evidenceCharts = {}; // Objek untuk menyimpan chart bukti

    // [PERBAIKAN] Menggunakan selector dari partial Anda
    $(document).on('click', '.view-evidence-btn', function() {
        var $button = $(this);
        var canvasId = $button.data('canvas-id');

        // [PERBAIKAN] Menggunakan ID container dari partial Anda
        var $container = $('#' + canvasId.replace('canvas', 'container'));

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
        var chartType = 'line'; // <-- Ini penting

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
                evidenceCharts[canvasId] = drawComparisonChart(ctx, allParsedData, chartType);
                $button.html('<i class="fa fa-bar-chart me-2"></i> {{__("admin.siswa.show_forum.show_attach")}}');
            })
            .catch(error => {
                alert('{{__("admin.siswa.show_forum.error")}}: ' + error.message);
                $button.html('<i class="fa fa-bar-chart me-2"></i> {{__("admin.siswa.show_forum.show_attach")}}');
            });
    });

    // --- [DARI SISWA] Bagian 4: Fungsi Helper (Disalin dari Practicum) ---

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
     * (Ini adalah fungsi Anda yang sudah benar)
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
                tension: 0.1,    // <-- Ini kuncinya (menggambar garis)
                pointRadius: 0   // <-- Ini kuncinya (tidak menggambar titik)
            });
        });

        // Gambar Chart dan return instance-nya
        return new Chart(ctx, {
            type: 'line', // <-- Ini kuncinya (memaksa tipe 'line')
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
