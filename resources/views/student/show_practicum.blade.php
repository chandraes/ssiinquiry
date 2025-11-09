@extends('layouts.app')
@section('title'){{ $subModule->title }}@endsection
@section('content')
<div class="container-fluid">
    @include('student.partials.grade_feedback_box')
    @php
        $instruction = $subModule->learningMaterials->first();
    @endphp

    <div class="card shadow-sm mb-4">
        {{-- ... (Kode Header Anda: tombol kembali, judul, deskripsi) ... --}}
        <div class="card-body">
            <h2 class="card-title"><i class="fa fa-flask text-success me-2"></i>{{ $subModule->title }}</h2>
            <p class="text-muted">{{ $subModule->description }}</p>
        </div>
    </div>

    @if($instruction)
    <div class="card shadow-sm mb-4">
        {{-- ... (Kode Petunjuk Anda) ... --}}
        <div class="card-header">
            <h5 class="mb-0">{{ __('admin.practicum.instructions') }}</h5>
        </div>
        <div class="card-body">
            <div class="rich-text-content p-2">{!! $instruction->content !!}</div>
        </div>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">{{ __('admin.practicum.upload_slots') }}</h5>
        </div>
        <div class="card-body">

            {{-- [BAGIAN BARU] Kontrol Grafik Perbandingan --}}
            <div class="border p-3 mb-4">
                <h5 class="mb-3">{{__('admin.siswa.show_practicum.chart')}}</h5>
                <p class="text-muted">{{__('admin.siswa.show_practicum.instruction_checklist')}}.</p>
                <button id="compare-charts-btn" class="btn btn-info">
                    <i class="fa fa-bar-chart me-2"></i> {{__('admin.siswa.show_practicum.compare_button')}}
                </button>

                {{-- Canvas Master untuk Perbandingan --}}
                <div class="mt-3" style="display:none;" id="comparison-chart-container">
                    <canvas id="comparison-chart-canvas"></canvas>
                </div>
            </div>
            {{-- [AKHIR BAGIAN BARU] --}}


            <p class="lead">{{__('admin.siswa.show_practicum.instruction_upload')}}.</p>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @forelse($subModule->practicumUploadSlots as $slot)
                @php
                // [PERBAIKAN] Gunakan '$submissions' (dari controller)
                $submission = $submissions->get($slot->id);
                $isSubmitted = $submission && $submission->file_path;
                $isDisabled = $currentProgress && $currentProgress->completed_at;
            @endphp

                <div class="card mb-3 {{ $submission ? 'border-success' : 'border-light' }}">
                    <div class="card-body">

                        <form action="{{ route('student.practicum.store', [$kelas->id, $subModule->id, $slot->id]) }}"
                              method="POST" enctype="multipart/form-data">
                            {{-- ... (Kode Form Unggah Anda - tidak berubah) ... --}}
                            @csrf
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <h5 class="mb-1">{{ $slot->label }}</h5>
                                    <small class="text-muted">{{ $slot->description }}</small>
                                </div>
                                <div class="col-md-5">
                                    <input type="file" name="practicum_file" class="form-control" required>
                                </div>
                                <div class="col-md-3 text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-upload me-2"></i> {{__('admin.siswa.show_practicum.upload')}}
                                    </button>
                                </div>
                            </div>
                        </form>

                        @if($errors->has('practicum_file') && session('failed_slot_id') == $slot->id)
                            <div class="alert alert-danger mt-2">{{ $errors->first('practicum_file') }}</div>
                        @endif

                        @if($submission)
                            <div class="alert alert-success mt-3 mb-0">
                                <i class="fa fa-check-circle me-2"></i>
                                {{__('admin.siswa.show_practicum.uploaded')}}: <strong>{{ $submission->original_filename }}</strong>
                                {{-- ... (Info ukuran file) ... --}}
                            </div>

                            {{-- [PERUBAHAN] Ganti Tombol "Lihat" dengan Checkbox "Bandingkan" --}}
                            <div class="form-check mt-2">
                                <input class="form-check-input compare-chart-checkbox" type="checkbox"
                                       value="{{ $submission->id }}"
                                       id="compare-check-{{ $slot->id }}"
                                       {{-- Simpan semua data yang kita butuhkan --}}
                                       data-url="{{ asset('storage/' . $submission->file_path) }}"
                                       data-type="{{ $slot->phyphox_experiment_type }}"
                                       data-label="{{ $slot->label }} ({{ $submission->original_filename }})">
                                <label class="form-check-label" for="compare-check-{{ $slot->id }}">
                                    {{__('admin.siswa.show_practicum.label')}}
                                </label>
                            </div>

                            <div class="individual-chart-container mt-3 border p-2" style="max-height: 350px; position: relative;">
                                {{-- Feedback saat memuat --}}
                               <div class="chart-loading-feedback text-center p-5"> {{-- <== SUDAH DIPERBAIKI --}}
                                    <i class="fa fa-spinner fa-spin me-2"></i> {{__('admin.siswa.show_practicum.loading_chart')}}
                                </div>

                                {{-- Canvas ini akan diisi oleh JavaScript saat halaman dimuat --}}
                                <canvas class="individual-chart-canvas"
                                        id="individual-chart-{{ $submission->id }}"
                                        {{-- Data ini akan dibaca oleh JS untuk mem-fetch CSV --}}
                                        data-url="{{ asset('storage/' . $submission->file_path) }}"
                                        data-label="{{ $slot->label }} ({{ $submission->original_filename }})"
                                        data-type="{{ $slot->phyphox_experiment_type }}"
                                        style="display: none;"></canvas>

                                {{-- Feedback jika gagal --}}
                                <div class="chart-error-feedback text-center p-5" style="display: none;">
                                    <i class="fa fa-exclamation-triangle text-danger me-2"></i> {{__('admin.siswa.show_practicum.failed_chart')}}
                                </div>
                            </div>

                        @endif
                    </div>
                </div>
            @empty
                <div class="alert alert-light text-center">
                    {{__('admin.siswa.show_practicum.no_slot')}}.
                </div>
            @endforelse
        </div>

        {{-- Footer Selesai Otomatis (Tidak berubah) --}}
        <div class="card-footer text-center">
            @if($currentProgress && $currentProgress->completed_at)
                {{-- ... (Info "Sudah Selesai") ... --}}
            @else
                <p class="text-muted mb-0">{{__('admin.siswa.show_practicum.finish_submodul')}}</p>
            @endif
        {{-- </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body pb-0 text-center"> --}}

            {{-- Cek apakah sub-modul ini SUDAH selesai --}}
            @if($currentProgress && $currentProgress->completed_at)

                <div class="alert alert-success mb-0">
                    <i class="fa fa-check-circle me-2"></i>
                    {{__('admin.siswa.show_learning.finish')}} {{ $currentProgress->completed_at->format('d M Y, H:i') }}.
                </div>

                <div class="card-footer d-flex flex-wrap justify-content-between align-items-center gap-3 mt-5">
                    {{-- Tombol Kembali --}}
                    <a href="{{ route('student.class.show', $kelas->id) }}" class="btn btn-secondary btn-lg flex-fill text-nowrap">
                        <i class="fa fa-arrow-left me-2"></i> {{__('admin.siswa.back_to_curriculum')}}
                    </a>
                    {{-- Tombol Tandai Selesai --}}
                    <form action="{{ route('student.submodule.complete', [$kelas->id, $subModule->id]) }}" method="POST" class="flex-fill text-end">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg w-100 text-nowrap">
                            {{__('admin.siswa.show_learning.button_finish')}} <i class="fa fa-arrow-right ms-2"></i>
                        </button>
                    </form>
                </div>
            @else
                {{-- <p class="lead">{{__('admin.siswa.show_learning.finish_instruction')}}.</p> --}}

                <div class="card-footer text-center mt-5">
                    <a href="{{ route('student.class.show', $kelas->id) }}" class="btn btn-secondary btn-lg">
                        <i class="fa fa-arrow-left me-2"></i> {{__('admin.siswa.back_to_curriculum')}}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection


{{-- ################################################################# --}}
{{--                           KODE JAVASCRIPT BARU                    --}}
{{-- ################################################################# --}}

@push('js')
<script>
    // Instance chart global untuk perbandingan
    var comparisonChart = null;

    // Tombol "Bandingkan" utama
    $('#compare-charts-btn').on('click', function() {
        var $button = $(this);
        var checkedBoxes = $('.compare-chart-checkbox:checked');
        var container = $('#comparison-chart-container');
        var canvas = $('#comparison-chart-canvas');
        var ctx = document.getElementById('comparison-chart-canvas').getContext('2d');

        // 1. Hancurkan chart lama (jika ada)
        if (comparisonChart) {
            comparisonChart.destroy();
        }

        // 2. Validasi Input
        if (checkedBoxes.length === 0) {
            alert('{{__("admin.siswa.show_practicum.alert_ceklist")}}.');
            container.slideUp();
            return;
        }

        // 3. Validasi Tipe Data (PENTING: Jangan bandingkan Amplitudo vs Spektrum)
        var firstType = $(checkedBoxes[0]).data('type');
        var allSameType = true;
        checkedBoxes.each(function() {
            if ($(this).data('type') !== firstType) {
                allSameType = false;
            }
        });

        if (!allSameType) {
            alert('{{__("admin.siswa.show_practicum.alert_file")}}.');
            container.slideUp();
            return;
        }

        // Tampilkan loading & container
        container.slideDown();
        $button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Memuat Data...');

        // 4. Siapkan array untuk "Promises" (karena fetch file itu asinkron)
        var fetchPromises = [];

        checkedBoxes.each(function() {
            var url = $(this).data('url');
            var label = $(this).data('label');

            // Masukkan "promise" dari setiap file ke array
            fetchPromises.push(
                fetchAndParseCsv(url, label, ";") // Gunakan delimiter ";"
            );
        });

        // 5. Jalankan semua promise sekaligus
        Promise.all(fetchPromises)
            .then(allParsedData => {
                // allParsedData adalah array: [{label: "...", csvData: [...]}, {label: "...", csvData: [...]}]

                // Tentukan tipe chart (paksa 'line' untuk perbandingan)
                // (Perbandingan 'bar' antar spektrum rumit jika sumbu X tidak sama)
                var chartType = 'line';

                // Gambar chart dengan semua data
                drawComparisonChart(ctx, allParsedData, chartType);

                $button.prop('disabled', false).html('<i class="fa fa-bar-chart me-2"></i> Bandingkan Grafik yang Dipilih');
            })
            .catch(error => {
                alert('Terjadi error: ' + error.message);
                $button.prop('disabled', false).html('<i class="fa fa-bar-chart me-2"></i> Bandingkan Grafik yang Dipilih');
            });
    });

    /**
     * Helper: Mengambil URL, mem-parsing CSV, dan me-return Promise
     */
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
                            // Kirim hasil (label + data) saat selesai
                            resolve({ label: label, csvData: results.data });
                        },
                        error: (err) => reject(new Error('Gagal mem-parsing ' + label + ': ' + err.message))
                    });
                })
                .catch(err => reject(err));
        });
    }

    /**
     * Helper: Menggambar BANYAK dataset di SATU chart
     */
    function drawComparisonChart(ctx, allParsedData, chartType) {

        // Palet warna agar setiap garis berbeda
        var colorPalette = [
            'rgb(75, 192, 192)', 'rgb(255, 99, 132)', 'rgb(54, 162, 235)',
            'rgb(255, 206, 86)', 'rgb(153, 102, 255)', 'rgb(255, 159, 64)'
        ];

        // 1. Ambil label sumbu dari file PERTAMA
        // (Asumsi semua file yang dibandingkankan punya unit yang sama)
        var xLabel = (allParsedData[0].csvData.length > 0) ? allParsedData[0].csvData[0][0] : 'Kolom 1';
        var yLabel = (allParsedData[0].csvData.length > 0) ? allParsedData[0].csvData[0][1] : 'Kolom 2';

        // 2. Buat array datasets untuk Chart.js
        var datasets = [];
        allParsedData.forEach((parsedFile, index) => {

            var dataRows = parsedFile.csvData.slice(1); // Lewati header

            // [SANGAT PENTING] Gunakan format {x, y}
            // Ini WAJIB agar chart bisa menggambar garis
            // dengan sumbu X (waktu/frekuensi) yang mungkin berbeda-beda
            var xyData = dataRows.map(row => ({
                x: parseFloat(row[0]), // Kolom 0 (Waktu/Frekuensi)
                y: parseFloat(row[1])  // Kolom 1 (Amplitudo/Spektrum)
            }));

            var color = colorPalette[index % colorPalette.length]; // Dapatkan warna unik

            datasets.push({
                label: parsedFile.label, // "Slot 1 (file.csv)"
                data: xyData,
                borderColor: color,
                backgroundColor: color + '33', // Transparan
                tension: 0.1,
                pointRadius: 0
            });
        });

        // 3. Gambar Chart
        comparisonChart = new Chart(ctx, {
            type: 'line', // Paksa 'line' untuk perbandingan terbaik
            data: {
                datasets: datasets // Masukkan array dataset yang sudah kita buat
            },
            options: {
                animation: false,
                scales: {
                    x: {
                        type: 'linear', // Gunakan sumbu linear untuk {x,y}
                        title: { display: true, text: xLabel }
                    },
                    y: {
                        title: { display: true, text: yLabel }
                    }
                }
            }
        });
    }

    function drawIndividualChart(ctx, parsedFile, chartType) {

         // 1. Ambil label sumbu dari header file
        var xLabel = (parsedFile.csvData.length > 0) ? parsedFile.csvData[0][0] : 'Kolom 1';
        var yLabel = (parsedFile.csvData.length > 0) ? parsedFile.csvData[0][1] : 'Kolom 2';

        // 2. Siapkan data
        var dataRows = parsedFile.csvData.slice(1); // Lewati header

        // Gunakan format {x, y}
        var xyData = dataRows.map(row => ({
            x: parseFloat(row[0]),
            y: parseFloat(row[1])
        }));

        // 3. Tentukan tipe chart (selalu 'line' untuk data {x,y})
        var TipeChartSebenarnya = 'line';

        // 4. Buat dataset
        var dataset = {
            label: parsedFile.label,
            data: xyData,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgb(75, 192, 192, 0.2)', // Warna transparan
            tension: 0.1,
            pointRadius: 0 // Tidak perlu titik untuk data yg banyak
        };

        // 5. Gambar Chart
        new Chart(ctx, {
            type: TipeChartSebenarnya,
            data: {
                datasets: [dataset] // Masukkan sebagai array 1 elemen
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                scales: {
                    x: {
                        type: 'linear', // Gunakan sumbu linear untuk {x,y}
                        title: { display: true, text: xLabel }
                    },
                    y: {
                        title: { display: true, text: yLabel }
                    }
                },
                plugins: {
                    legend: {
                        display: false // Sembunyikan legenda, karena hanya 1 data
                    }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {

        loadIndividualCharts();


        const completeForm = document.querySelector('form[action="{{ route('student.submodule.complete', [$kelas->id, $subModule->id]) }}"]');
        const completeButton = completeForm.querySelector('button[type="submit"]');

        // Ubah label tombol
        completeButton.innerHTML = `<i class="fa fa-arrow-right me-2"></i> {{__('admin.siswa.show_reflection.next_submodule')}}`;

        completeForm.addEventListener('submit', function(e) {
            e.preventDefault(); // cegah submit langsung

            // Cek semua slot upload apakah sudah ada file yang diunggah
            const totalSlots = document.querySelectorAll('.card-body form[action*="student/practicum/store"]').length;
            const uploadedSlots = document.querySelectorAll('.alert.alert-success').length;

            if (uploadedSlots < totalSlots) {
                Swal.fire({
                    icon: 'error',
                    title: '{{__("admin.siswa.show_practicum.swal.failed.title")}}',
                    text: '{{__("admin.siswa.show_practicum.swal.failed.text")}}',
                    confirmButtonColor: '#d33',
                    confirmButtonText: '{{__("admin.button.ok")}}'
                });
                return;
            }

            // Jika semua sudah lengkap, tampilkan konfirmasi
            Swal.fire({
                title: '{{__("admin.siswa.show_practicum.swal.success.title")}}',
                text: '{{__("admin.siswa.show_practicum.swal.success.text")}}',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: '{{__("admin.siswa.show_reflection.forward")}}',
                cancelButtonText: '{{__("admin.button.cancel")}}'
            }).then((result) => {
                if (result.isConfirmed) {
                    completeForm.submit(); // submit jika konfirmasi
                }
            });
        });
    });

    function loadIndividualCharts() {
        // 1. Temukan semua canvas individual
        var $canvases = $('.individual-chart-canvas');

        if ($canvases.length === 0) return; // Tidak ada yang perlu dimuat

        // 2. Iterasi dan muat data untuk masing-masing
        $canvases.each(function() {
            var $canvas = $(this);
            var $container = $canvas.closest('.individual-chart-container');
            var $loading = $container.find('.chart-loading-feedback');
            var $error = $container.find('.chart-error-feedback');

            // Ambil data dari atribut data-*
            var url = $canvas.data('url');
            var label = $canvas.data('label');
            var type = $canvas.data('type');
            var ctx = $canvas[0].getContext('2d');

            // 3. Ambil & parse data (gunakan fungsi helper yang sudah ada)
            fetchAndParseCsv(url, label, ";")
                .then(parsedData => {
                    // 4. Gambar grafik individual
                    drawIndividualChart(ctx, parsedData, type);

                    // 5. Tampilkan canvas, sembunyikan loading
                    $loading.hide();
                    $canvas.show();
                })
                .catch(error => {
                    console.error("Gagal memuat chart individual:", error);
                    // Tampilkan pesan error
                    $loading.hide();
                    $error.show();
                });
        });
    }
</script>
@endpush
