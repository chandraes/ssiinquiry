@extends('layouts.app')

@section('title')
{{ __('admin.modul.page_title') }}
@endsection

@section('content')
@include('swal')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="card-title">{{ __('admin.modul.card_title') }}</h2>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

        {{-- Kartu "Tambah Modul" (Hanya Admin) --}}
        {{-- Kita asumsikan hanya Admin yang boleh MEMBUAT modul baru --}}
        @if(Auth::user()->hasRole('Administrator'))
        <div class="col">
            <div class="card h-100 shadow-sm border-dashed"
                style="cursor: pointer; border-style: dashed; border-width: 2px;" data-bs-toggle="modal"
                data-bs-target="#createModal">
                <div class="card-body d-flex justify-content-center align-items-center">
                    <div class="text-center text-primary">
                        <i class="fa fa-plus fa-3x mb-3"></i>
                        <h5 class="card-title mb-0">{{ __('admin.modul.add_new') }}</h5>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @forelse($moduls as $modul)
        <div class="col">
            <div class="card h-100 shadow-sm">
                @php
                if ($modul->image) {
                $imageUrl = asset('storage/' . $modul->image);
                } else {
                // 1. Ambil teks dan bersihkan
                $text = e($modul->getTranslation('judul', 'en'));

                // 2. Buat string SVG
                // Kita gunakan warna abu-abu Bootstrap agar serasi
                $svg = '<svg width="400" height="250" xmlns="http://www.w3.org/2000/svg"
                    style="background-color:#e9ecef;">';
                    $svg .= '<text x="50%" y="50%" font-family="Arial, sans-serif" font-size="20" fill="#6c757d"
                        text-anchor="middle" dominant-baseline="middle">';
                        $svg .= $text;
                        $svg .= '</text></svg>';

                // 3. Encode sebagai Data URI
                // Ini adalah gambar yang dibuat dari teks
                $imageUrl = 'data:image/svg+xml;base64,' . base64_encode($svg);
                }
                @endphp

                {{--
                Tidak ada yang perlu diubah di sini.
                $imageUrl sekarang berisi gambar asli ATAU data SVG.
                --}}
                <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $modul->judul }}"
                    style="height: 200px; object-fit: cover;">

                <div class="card-body">
                    <h5 class="card-title">{{ $modul->judul }}</h5>
                    <p class="card-text text-muted">
                        {!! Str::limit($modul->deskripsi, 100) !!}
                    </p>
                </div>

                <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
                    <a href="{{ route('modul.show', $modul->id) }}" class="btn btn-primary btn-lg">
                        {{ __('admin.modul.view_details') }}
                    </a>

                    <span class="badge bg-light text-dark">
                        <i class="fa fa-book-open me-1"></i> {{ $modul->kelas_count }} Kelas
                    </span>

                    {{-- [PERBAIKAN DI SINI] --}}
                    {{-- Tombol Aksi (Admin ATAU Guru) --}}
                    {{-- Kita gunakan hasAnyRole dari trait Anda --}}
                    {{-- @if(Auth::user()->hasAnyRole(['admin', 'Guru']))÷ --}}
                    @role(['guru', 'admin'])
                    <div>
                        {{-- Tombol Edit --}}
                        <button class="btn btn-warning btn-lg" title="{{ __('admin.button.edit') }}"
                            data-bs-toggle="modal" data-bs-target="#editModal"
                            data-url="{{ route('modul.json', $modul->id) }}"
                            data-update-url="{{ route('modul.update', $modul->id) }}" onclick="editButton(this)">
                            <i class="fa fa-pencil"></i>
                        </button>

                        {{-- Tombol Delete (Hanya Admin) --}}
                        <button class="btn btn-danger btn-lg" title="{{ __('admin.button.delete') }}"
                            onclick="deleteButton({{ $modul->id }})">
                            <i class="fa fa-trash"></i>
                        </button>

                        <form action="{{ route('modul.delete', $modul->id) }}" method="POST"
                            id="delete-form-{{ $modul->id }}">
                            @csrf
                            @method('delete')
                        </form>
                    </div>
                    @endrole
                    {{-- @endif --}}
                    {{-- [AKHIR PERBAIKAN] --}}
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                {{ __('admin.modul.no_modules') }}
            </div>
        </div>
        @endforelse

    </div>
</div>

{{-- Modal Create --}}
@include('modul.create')

{{-- Modal Edit --}}
@include('modul.edit')

@endsection
@push('js')
<script>
// [PERUBAHAN DI SINI] Inisialisasi TinyMCE
    tinymce.init({
        // Gunakan selector class untuk menargetkan SEMUA editor
        selector: 'textarea.rich-text-editor',
        plugins: 'lists link code fullscreen',
        toolbar: 'undo redo | blocks | bold italic | bullist numlist | link code | fullscreen',
        menubar: false,
        height: 250,
        license_key: 'gpl',
    });

$(document).ready(function() {
    // --- Inisialisasi Select2 Phyphox untuk Modal EDIT ---
    // Pastikan Select2 diinisialisasi SEBELUM editButton dipanggil
    $('#edit_phyphox_id').select2({
        dropdownParent: $('#editModal'),
        placeholder: 'Pilih alat Phyphox',
        allowClear: true,
        theme: 'bootstrap-5' // Sesuaikan tema jika perlu
    });

    // --- Tombol Simpan Perubahan (Update) ---
     $('#btnUpdate').on('click', function() {
        // Tampilkan konfirmasi Swal
         Swal.fire({
            title: '{{ __("admin.swal.update_title") }}',
            text: "{{ __("admin.swal.update_text") }}",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '{{ __("admin.swal.update_confirm") }}',
            cancelButtonText: '{{ __("admin.swal.cancel") }}',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form jika dikonfirmasi
                 $('#editForm').submit();
            }
        });
    });

     // Fungsi deleteButton Anda (sudah benar)
     window.deleteButton = function(id) { // Jadikan global agar bisa dipanggil dari HTML
        Swal.fire({
            title: '{{ __("admin.swal.delete_title") }}',
            text: "{{ __("admin.swal.delete_text") }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: '{{ __("admin.swal.delete_confirm") }}',
            cancelButtonText: '{{ __("admin.swal.cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }

    // Fungsi Preview Gambar saat file dipilih di modal edit
    $('#edit_image').on('change', function(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('edit_image_preview');
            output.src = reader.result;
            output.style.display = 'inline-block'; // Tampilkan preview baru
        };
        if(event.target.files[0]){
            reader.readAsDataURL(event.target.files[0]);
            $('#edit_image_current').hide(); // Sembunyikan gambar lama jika ada file baru
        } else {
             $('#edit_image_preview').hide().attr('src', '#'); // Sembunyikan preview baru
             // Tampilkan lagi gambar lama jika ada
             if ($('#edit_image_current').attr('src') && $('#edit_image_current').attr('src') !== '#') {
                 $('#edit_image_current').show();
             }
        }
    });

}); // Akhir document ready

    /**
     * [FUNGSI DIPERBARUI] Untuk mengisi modal edit
     * Mengisi field terjemahan, select2 phyphox, dan preview gambar.
     */
     // Jadikan global agar bisa dipanggil dari HTML
     window.editButton = function(button) {
        const url = $(button).data('url'); // URL JSON
        const updateUrl = $(button).data('update-url'); // URL Update

        const form = document.getElementById('editForm');
        form.action = updateUrl;

        $.get(url, function (data) {
            // data = { id: ..., judul: {id, en}, deskripsi: {id, en}, phyphox_ids: [], image_url: '...' }

            // 1. Isi field judul dan deskripsi
            $('#edit_judul_id').val(data.judul.id);
            $('#edit_judul_en').val(data.judul.en);
            $('#edit_deskripsi_id').val(data.deskripsi.id);
            $('#edit_deskripsi_en').val(data.deskripsi.en);

            // === Update TinyMCE jika sudah aktif ===
            if (tinymce.get('edit_deskripsi_id')) {
                tinymce.get('edit_deskripsi_id').setContent(data.deskripsi.id || '');
            }
            if (tinymce.get('edit_deskripsi_en')) {
                tinymce.get('edit_deskripsi_en').setContent(data.deskripsi.en || '');
            }
            
            // 2. Isi Select2 Phyphox
             if(data.phyphox_ids && Array.isArray(data.phyphox_ids)) {
                 $('#edit_phyphox_id').val(data.phyphox_ids).trigger('change');
             } else {
                 $('#edit_phyphox_id').val(null).trigger('change'); // Kosongkan jika tidak ada
             }

            // 3. Reset dan Tampilkan Preview Gambar
            $('#edit_image').val(''); // Kosongkan input file
            $('#edit_image_preview').hide().attr('src', '#'); // Sembunyikan preview baru
            if (data.image_url) {
                $('#edit_image_current').attr('src', data.image_url).show(); // Tampilkan gambar saat ini
            } else {
                 $('#edit_image_current').hide().attr('src', '#'); // Sembunyikan jika tidak ada gambar
            }

            // 4. Tampilkan modal-nya
            $('#editModal').modal('show');

        }).fail(function() {
            Swal.fire('Error', 'Gagal mengambil data modul.', 'error');
        });
    }

</script>
@endpush
