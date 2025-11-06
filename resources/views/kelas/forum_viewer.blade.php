@extends('layouts.app')
@section('title'){{__('admin.review_forum.title')}} : {{ $kelas->nama_kelas }}@endsection
@push('css')
<style>
    #pdf-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.75);
        z-index: 9998;
        display: none; /* Sembunyi secara default */
        color: white;
        font-size: 1.5rem;
    }
    .overlay-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }
    .overlay-content .fa-spinner {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
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
<div id="pdf-overlay">
    <div class="overlay-content">
        <i class="fa fa-spinner fa-spin"></i>
        <p>{{__('admin.loading_state_title')}}</p>
        <small>{{__('admin.loading_state_subtitle')}}</small>
    </div>
</div>
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
           @if($selectedSubModule)
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-success" id="download-pdf-btn"
                            data-kelas-name="{{ $kelas->nama_kelas }}"
                            data-module-name="{{ $kelas->modul->judul }}"
                            data-submodule-name="{{ $selectedSubModule->title }}"
                            data-filename="Transkrip-Forum-{{ Str::slug($kelas->nama_kelas, '-') }}-{{ $selectedSubModule->id }}.pdf">
                        <i class="fa fa-file-pdf-o me-2"></i> Download Transkrip PDF
                    </button>
                    {{-- Tombol loading lama (pdf-loading-state) kita HAPUS --}}
                </div>
            @endif
            @if(!$selectedSubModule)
                <div class="alert alert-info text-center">
                    {{__('admin.review_forum.instruction')}}.
                </div>
            @else
                {{-- [PERBAIKAN] Tampilkan Tampilan Satu Kolom --}}
                <div class="forum-viewer-container" id="forum-pdf-content">
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
<script src="{{asset('assets/js/html2canvas.min.js')}}"></script>
<script src="{{asset('assets/js/jspdf.umd.min.js')}}"></script>
<script>
window.jspdf = window.jspdf.jsPDF;

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

   $('#download-pdf-btn').on('click', async function() {
        var $btn = $(this);
        var $overlay = $('#pdf-overlay');

        var kelasName = $btn.data('kelas-name');
        var moduleName = $btn.data('module-name');
        var subModuleName = $btn.data('submodule-name');
        var fileName = $btn.data('filename');

        // 1. Tampilkan Overlay (SAMA)
        $overlay.fadeIn(200);

        try {
            // 2. Paksa render semua grafik (SAMA)
            await renderAllEvidenceCharts();

            // 3. Beri waktu DOM untuk update (SAMA)
            await new Promise(resolve => setTimeout(resolve, 1000));

            // 4. === [LOGIKA PDF BARU V3] Iterasi per Elemen ===

            // --- Inisialisasi PDF dan Margin (SAMA) ---
            const pdf = new jspdf('p', 'mm', 'a4');
            const topMargin = 20;
            const bottomMargin = 20;
            const leftMargin = 15;
            const rightMargin = 15;
            const postMargin = 5; // Spasi antar postingan (mm)

            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = pdf.internal.pageSize.getHeight();
            const contentWidth = pdfWidth - leftMargin - rightMargin;

            // Tinggi area konten yang bisa dipakai di *satu halaman*
            const pageContentHeight = pdfHeight - topMargin - bottomMargin;

            // --- Tambahkan Header Dokumen (HALAMAN 1) (SAMA) ---
            pdf.setFontSize(18);
            pdf.setFont('helvetica', 'bold');
            pdf.text("Transkrip Forum", leftMargin, topMargin);

            pdf.setFontSize(12);
            pdf.setFont('helvetica', 'normal');
            pdf.text("Modul: " + moduleName, leftMargin, topMargin + 10);
            pdf.text("Kelas: " + kelasName, leftMargin, topMargin + 15);
            pdf.text("Submodul: " + subModuleName, leftMargin, topMargin + 20);

            pdf.setLineWidth(0.5);
            pdf.line(leftMargin, topMargin + 23, pdfWidth - rightMargin, topMargin + 23);

            // Tentukan posisi Y awal (tepat di bawah header)
            let currentY = topMargin + 25;

            // --- Ambil SEMUA postingan utama (top-level) ---
            // Ini adalah elemen .post-wrapper yang ada di blade 'forum_post.blade.php'
            const postElements = document.querySelectorAll('#forum-pdf-content > .forum-column > .post-wrapper');

            console.log(`Mulai memproses ${postElements.length} postingan utama...`);

            // --- Loop setiap postingan menggunakan 'for...of' (untuk 'await') ---
            for (const postEl of postElements) {

                // 1. Screenshot HANYA elemen postingan ini
                const canvas = await html2canvas(postEl, {
                    scale: 2,
                    useCORS: true,
                    backgroundColor: '#ffffff'
                });

                // 2. Hitung dimensinya dalam mm
                const imgData = canvas.toDataURL('image/png');
                const imgHeight = canvas.height;
                const imgWidth = canvas.width;

                // Hitung tinggi postingan di PDF sambil menjaga rasio
                const postHeightInPdf = (imgHeight / imgWidth) * contentWidth;

                // 3. === LOGIKA PAGE BREAK (PENCEGAH PEMOTONGAN) ===

                // Cek apakah postingan ini *sendiri* lebih tinggi dari 1 halaman
                const isPostTallerThanPage = postHeightInPdf > pageContentHeight;

                // Cek apakah menambahkan postingan ini akan 'overflow'
                const willOverflow = (currentY + postHeightInPdf) > (pdfHeight - bottomMargin);

                // Cek apakah kita *masih* di awal halaman (tepat di bawah header)
                // Ini untuk mencegah infinite loop jika 1 post > 1 halaman
                const isAtTopOfPage = (currentY === (topMargin + 25)) || (currentY === topMargin);

                if (willOverflow && !isAtTopOfPage) {
                    // **Kondisi Terpenuhi:**
                    // 1. Postingan ini akan terpotong (willOverflow)
                    // 2. Kita tidak sedang di awal halaman (isAtTopOfPage = false)

                    // **Tindakan:**
                    // Pindah ke halaman baru, reset Y ke atas
                    pdf.addPage();
                    currentY = topMargin;
                }

                // 4. Tambahkan gambar postingan ke PDF
                // jsPDF secara internal akan menangani jika 'postHeightInPdf'
                // lebih besar dari 'pageContentHeight' (kasus postingan super panjang)
                pdf.addImage(imgData, 'PNG', leftMargin, currentY, contentWidth, postHeightInPdf);

                // 5. Hitung Posisi Y baru
                // Kita harus menghitung di mana 'addImage' selesai menempelkan gambar.

                // (currentY - topMargin) adalah posisi relatif di dalam area konten
                let totalHeightWithPost = (currentY - topMargin) + postHeightInPdf;

                // Jika Y awal BUKAN di topMargin (misal halaman 1)
                if (currentY > topMargin) {
                    // Kita pakai Y dari header halaman 1
                    totalHeightWithPost = (currentY - (topMargin + 25)) + postHeightInPdf;
                }

                // Sisa tinggi di halaman terakhir yang ditempati oleh postingan ini
                let heightOnLastPage = totalHeightWithPost % pageContentHeight;

                if (heightOnLastPage === 0 && totalHeightWithPost > 0) {
                     // Jika pas di akhir halaman
                    heightOnLastPage = pageContentHeight;
                }

                // Posisi Y baru adalah di atas + sisa tinggi itu
                currentY = topMargin + heightOnLastPage;

                // Tambahkan margin *antar* postingan
                currentY += postMargin;

            } // --- Akhir dari Loop 'for...of' ---

            console.log("Semua postingan telah diproses.");

            const totalPages = pdf.internal.getNumberOfPages();
            const footerFontSize = 10;

            for (let i = 1; i <= totalPages; i++) {
                pdf.setPage(i); // Pindah ke halaman i

                // Siapkan teks footer
                const pageText = `Halaman ${i} dari ${totalPages}`;

                // Atur font untuk footer (bisa dibuat abu-abu)
                pdf.setFontSize(footerFontSize);
                pdf.setFont('helvetica', 'normal');
                pdf.setTextColor(150); // 0 = hitam, 255 = putih

                // Hitung lebar teks untuk perataan kanan
                const textWidth = pdf.getStringUnitWidth(pageText) * footerFontSize / pdf.internal.scaleFactor;

                // Hitung Posisi X (Kanan)
                // (Lebar Halaman - Margin Kanan - Lebar Teks)
                const xPos = pdfWidth - rightMargin - textWidth;

                // Hitung Posisi Y (Bawah)
                // (Tinggi Halaman - 10mm dari bawah)
                // Ini aman karena bottomMargin kita 20mm
                const yPos = pdfHeight - 10;

                // Tulis teks ke halaman
                pdf.text(pageText, xPos, yPos);
            }

            // 7. Simpan file (SAMA)
            pdf.save(fileName);

        } catch (error) {
            console.error("Gagal membuat PDF:", error);
            alert('Terjadi kesalahan saat membuat PDF: ' + error.message);
        } finally {
            // 8. Sembunyikan Overlay dan Chart (SAMA)
            $overlay.fadeOut(200);
            $('.evidence-list .mt-3').slideUp();
        }
    });

    /**
     * Helper Baru: Memaksa render semua chart.
     * Fungsi ini akan mencari SEMUA tombol "view evidence",
     * mengambil datanya, dan memanggil fungsi fetch/draw Anda.
     */
    async function renderAllEvidenceCharts() {
        console.log("Mulai me-render semua grafik untuk PDF...");

        let masterPromiseList = [];

        // Loop setiap tombol "view evidence" yang ada di halaman
        $('.view-evidence-btn').each(function() {
            var $button = $(this);
            var canvasId = $button.data('canvas-id');
            var evidenceJson = $button.data('evidence-json');
            var $container = $('#' + canvasId.replace('canvas', 'container'));

            if (!evidenceJson || evidenceJson.length === 0) {
                return; // Lewati jika tidak ada bukti
            }

            // Hancurkan chart lama jika ada (untuk bersih-bersih)
            if (evidenceCharts[canvasId]) {
                evidenceCharts[canvasId].destroy();
            }

            // Paksa tampilkan container
            $container.slideDown();

            var ctx = document.getElementById(canvasId).getContext('2d');

            // Siapkan promise-promise untuk fetch CSV
            var fetchPromises = [];
            evidenceJson.forEach(function(evidence) {
                fetchPromises.push(
                    fetchAndParseCsv(evidence.url, evidence.label, ";")
                );
            });

            // Buat "promise utama" yang akan berjalan SETELAH semua CSV
            // untuk tombol INI selesai di-fetch dan di-gambar
            var chartRenderPromise = Promise.all(fetchPromises)
                .then(allParsedData => {
                    // Panggil helper LAMA Anda untuk menggambar chart
                    evidenceCharts[canvasId] = drawComparisonChart(ctx, allParsedData, 'line');
                    console.log("Grafik " + canvasId + " selesai di-render.");
                })
                .catch(error => {
                    console.error("Gagal render grafik " + canvasId, error);
                    // Jangan hentikan proses, biarkan PDF lanjut
                    // (grafik yang gagal akan tampil kosong)
                });

            // Tambahkan promise render chart ini ke daftar master
            masterPromiseList.push(chartRenderPromise);
        });

        // Tunggu ("await") SEMUA promise render dari SEMUA tombol selesai
        await Promise.all(masterPromiseList);

        console.log("Semua grafik telah selesai di-render.");
    }

}); // <-- Akhir dari jQuery(document).ready()
</script>
@endpush
