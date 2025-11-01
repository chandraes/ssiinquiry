@extends('layouts.app')

@section('title')
{{ __('admin.modul.detail.title') }}: {{ $modul->judul }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-5">
            <a href="{{ route('modul') }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left me-1"></i> {{ __('admin.button.back') }}
            </a>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('admin.modul.detail.module_info') }}</h5>
                </div>
                <div class="card-body">
                    {{-- Spatie otomatis menampilkan bahasa yang aktif --}}
                    <h4>{{ $modul->judul }}</h4>
                    <p class="text-muted">
                        {{ $modul->deskripsi }}
                    </p>
                    <hr>
                    <p>
                        <strong>{{__('admin.modul.detail.tools')}} :</strong>
                        {{-- Asumsi phyphox_id adalah array ID --}}
                        <span class="badge bg-info">{{ count($modul->phyphox_id ?? []) }} Alat</span>
                    </p>
                    <p>
                        <strong>{{__('admin.modul.detail.owner')}} : {{ $modul->owner->name ?? 'N/A' }} </strong>
                        {{-- Baris ini dari kode Anda sebelumnya --}}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('admin.modul.detail.submodule_list') }}</h5>

                    {{-- Tombol memicu modal input sub-modul --}}
                    {{-- [DIUBAH] Tombol "Tambah Sub Modul" sekarang menjadi Dropdown --}}
                    <div class="dropdown">
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="addSubModulMenu"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-plus me-2"></i>{{ __('admin.modul.detail.add_submodule') }}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="addSubModulMenu">
                            {{-- Tombol ini akan membuka modal yang sama, tapi mengirim data-type 'learning' --}}
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#createSubModulModal" data-type="learning">
                                    {{__('admin.modul.detail.dropdown_materi')}}
                                </a>
                            </li>
                            {{-- Tombol ini akan membuka modal yang sama, tapi mengirim data-type 'reflection' --}}
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#createSubModulModal" data-type="reflection">
                                    {{__('admin.modul.detail.dropdown_question')}}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#createSubModulModal" data-type="practicum">
                                    {{__('admin.modul.detail.dropdown_practicum')}}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" 
                                    data-bs-target="#createSubModulModal" data-type="forum">
                                    {{__('admin.modul.detail.dropdown_forum')}}
                                </a>
                            </li>
                            {{-- <li><a class="dropdown-item" href="#" data-type="forum">Sub Modul Forum (Segera)</a>
                            </li> --}}
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        {{-- Loop semua sub-modul yang sudah di-load dari controller --}}
                        @forelse($modul->subModules as $subModul)

                        {{-- [PERUBAHAN] Item sekarang menjadi link <a> --}}
                            <a href="{{ route('submodul.show', $subModul->id) }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ $subModul->order }}. {{ $subModul->title }}</h6>
                                    <small class="text-muted">{{ $subModul->description }}</small>
                                </div>

                                {{-- [PERUBAHAN] Kumpulan Tombol Aksi (Edit & Delete) --}}
                                {{-- Hentikan klik agar link <a> tidak terpicu --}}
                                    <div onclick="event.preventDefault();">
                                        <button class="btn btn-warning btn-sm"
                                            title="{{ __('admin.button.edit') }}"
                                            data-url="{{ route('submodul.show.json', $subModul->id) }}"
                                            data-update-url="{{ route('submodul.update', $subModul->id) }}"
                                            onclick="editSubModul(this)">
                                            <i class="fa fa-pencil"></i>
                                        </button>

                                        <button class="btn btn-danger btn-sm"
                                            title="{{ __('admin.button.delete') }}"
                                            onclick="deleteSubModul({{ $subModul->id }})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </a>

                                <form id="delete-form-{{ $subModul->id }}"
                                    action="{{ route('submodul.destroy', $subModul->id) }}" method="POST"
                                    class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                @empty
                                <div class="list-group-item text-center">
                                    {{ __('admin.modul.detail.no_submodule') }}
                                </div>
                                @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Memuat Modal Create (Anda sudah punya file ini) --}}
@include('submodul.create_modal')
{{-- Memuat Modal Edit (dari jawaban saya sebelumnya) --}}
@include('submodul.edit_modal')
@endsection
@push('js')
<script>

    /**
     * 1. FUNGSI UNTUK MENGISI MODAL EDIT (DIBERSIHKAN)
     * Hanya mengisi field dasar + max_points.
     */
    function editSubModul(button) {
        var modal = $('#editSubModulModal');
        var form = $('#editSubModulForm');
        var dataUrl = $(button).data('url');
        var updateUrl = $(button).data('update-url');

        form.attr('action', updateUrl);

        // Ambil data JSON (sekarang hanya berisi data dasar)
        $.get(dataUrl, function(data) {
            // === Mengisi Data Umum ===
            modal.find('#edit_submodul_type').val(data.type); // Penting!
            modal.find('#edit_sub_title_id').val(data.title.id);
            modal.find('#edit_sub_title_en').val(data.title.en);
            modal.find('#edit_sub_description_id').val(data.description.id);
            modal.find('#edit_sub_description_en').val(data.description.en);
            modal.find('#edit_sub_order').val(data.order);
            modal.find('#edit-max-points').val(data.max_points);

            // === FIELD FORUM DIHAPUS DARI SINI ===

            // === Logika Tampilkan/Sembunyikan untuk Poin Maksimal ===
            var maxPointsField = modal.find('#edit_max_points_wrapper');
            var maxPointsInput = modal.find('#edit-max-points');

            if (data.type === 'learning') {
                maxPointsField.addClass('d-none');
                maxPointsInput.prop('required', false);
            } else {
                maxPointsField.removeClass('d-none');
                maxPointsInput.prop('required', true);
            }

            // Tampilkan modal
            modal.modal('show');

        }).fail(function() {
            Swal.fire('Error', 'Gagal mengambil data sub modul.', 'error');
        });
    }

    /**
     * 2. EVENT LISTENER UNTUK MODAL CREATE (DIBERSIHKAN)
     * Hanya mengurus Poin Maksimal.
     */
    $('#createSubModulModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var subModulType = button.data('type');
        var modal = $(this);

        modal.find('#create_submodul_type').val(subModulType);

        // Temukan field poin
        var maxPointsField = modal.find('#create_max_points_wrapper');
        var maxPointsInput = modal.find('#create-max-points');

        // === LOGIKA FORUM DIHAPUS DARI SINI ===

        // Logika Poin Maksimal
        if (subModulType === 'learning') {
            maxPointsField.addClass('d-none');
            maxPointsInput.prop('required', false);
            maxPointsInput.val(0);
        } else {
            maxPointsField.removeClass('d-none');
            maxPointsInput.prop('required', true);
            maxPointsInput.val(10);
        }
    });

    /**
     * 3. Hancurkan TinyMCE saat modal ditutup
     * (SEMUA LOGIKA TINYMCE DIHAPUS DARI SINI)
     */
    // ... Tidak ada lagi yang perlu dilakukan di sini ...


    /**
     * 4. FUNGSI UNTUK KONFIRMASI SUBMIT FORM (SweetAlert)
     * (Disederhanakan, 'tinymce.triggerSave()' dihapus)
     */
    $(document).ready(function() {

        // Konfirmasi untuk form 'create'
        $('#storeSubModulForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            // tinymce.triggerSave() DIHAPUS
            Swal.fire({
                title: '{{ __("admin.swal.save_title") }}',
                text: "{{ __("admin.swal.save_text") }}",
                icon: 'question', showCancelButton: true,
                confirmButtonText: '{{ __("admin.swal.save_confirm") }}',
                cancelButtonText: '{{ __("admin.swal.cancel") }}',
            }).then((result) => { if (result.isConfirmed) { form.submit(); } });
        });

        // Konfirmasi untuk form 'edit'
        $('#editSubModulForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            // tinymce.triggerSave() DIHAPUS
            Swal.fire({
                title: '{{ __("admin.swal.update_title") }}',
                text: "{{ __("admin.swal.update_text") }}",
                icon: 'question', showCancelButton: true,
                confirmButtonText: '{{ __("admin.swal.update_confirm") }}',
                cancelButtonText: '{{ __("admin.swal.cancel") }}',
                reverseButtons: true
            }).then((result) => { if (result.isConfirmed) { form.submit(); } });
        });
    });

    /**
     * 5. FUNGSI UNTUK KONFIRMASI DELETE (SweetAlert)
     * (Tidak berubah)
     */
    function deleteSubModul(id) {
        Swal.fire({
            title: '{{ __("admin.swal.delete_title") }}',
            text: "{{ __("admin.swal.delete_text") }}",
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
            confirmButtonText: '{{ __("admin.swal.delete_confirm") }}',
            cancelButtonText: '{{ __("admin.swal.cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endpush
