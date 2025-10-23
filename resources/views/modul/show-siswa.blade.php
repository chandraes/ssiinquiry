@extends('layouts.app')

@section('title')
{{ __('admin.modul_detail.title') }}: {{ $modul->judul }}
@endsection

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('admin.modul_detail.submodule_list') }}</h5>

                    {{-- Tombol memicu modal input sub-modul --}}
                    {{-- [DIUBAH] Tombol "Tambah Sub Modul" sekarang menjadi Dropdown --}}
                    <div class="dropdown">
                        <ul class="dropdown-menu" aria-labelledby="addSubModulMenu">
                            {{-- Tombol ini akan membuka modal yang sama, tapi mengirim data-type 'learning' --}}
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#createSubModulModal" data-type="learning">
                                    Sub Modul Materi (Video, Teks, dll)
                                </a>
                            </li>
                            {{-- Tombol ini akan membuka modal yang sama, tapi mengirim data-type 'reflection' --}}
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#createSubModulModal" data-type="reflection">
                                    Sub Modul Pertanyaan Refleksi
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
                            <a href="{{ route('siswa.submodul.show', $subModul->id) }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ $subModul->order }}. {{ $subModul->title }}</h6>
                                    <small class="text-muted">{{ $subModul->description }}</small>
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
                                    {{ __('admin.modul_detail.no_submodule') }}
                                </div>
                                @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
{{-- JavaScript untuk mengaktifkan modal Edit dan Delete --}}
<script>
    /**
     * 1. FUNGSI UNTUK MENGISI MODAL EDIT
     * Dipanggil oleh tombol "Edit"
     */
    function editSubModul(button) {
        var modal = $('#editSubModulModal');
        var form = $('#editSubModulForm');

        // Ambil URL dari data- attribute
        var dataUrl = $(button).data('url');
        var updateUrl = $(button).data('update-url');

        // Set action form modal edit
        form.attr('action', updateUrl);

        // Ambil data JSON dari controller
        $.get(dataUrl, function(data) {
            // 'data' adalah JSON dari SubModulController@showJson

            // Isi semua input di modal
            modal.find('#edit_sub_title_id').val(data.title.id);
            modal.find('#edit_sub_title_en').val(data.title.en);

            modal.find('#edit_sub_description_id').val(data.description.id);
            modal.find('#edit_sub_description_en').val(data.description.en);

            modal.find('#edit_sub_order').val(data.order);

            // Tampilkan modal
            modal.modal('show');

        }).fail(function() {
            Swal.fire('Error', 'Gagal mengambil data sub modul.', 'error');
        });
    }

    $('#createSubModulModal').on('show.bs.modal', function (event) {
        // Ambil tombol yang di-klik
        console.log('masuk');
        var button = $(event.relatedTarget);

        // Ambil data-type dari tombol itu (mis: 'learning' or 'reflection')
        var subModulType = button.data('type');
        console.log(subModulType);
        // Temukan hidden input di dalam modal
        var modal = $(this);
        var typeInput = modal.find('#create_submodul_type');

        // Set value dari hidden input
        typeInput.val(subModulType);
    });

    /**
     * 2. FUNGSI UNTUK KONFIRMASI UPDATE (SweetAlert)
     * Dijalankan saat form edit di-submit
     */
    $(document).ready(function() {
        $('#editSubModulForm').on('submit', function(e) {
            e.preventDefault(); // Hentikan submit form standar
            var form = this;

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
                    form.submit(); // Lanjutkan submit form
                }
            });
        });

        // Anda juga bisa tambahkan konfirmasi untuk form 'create'
        $('#storeSubModulForm').on('submit', function(e) {
            e.preventDefault(); // Hentikan submit form standar
            var form = this;

            Swal.fire({
                title: '{{ __("admin.swal.save_title") }}',
                text: "{{ __("admin.swal.save_text") }}",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '{{ __("admin.swal.save_confirm") }}',
                cancelButtonText: '{{ __("admin.swal.cancel") }}',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Lanjutkan submit form
                }
            });
        });
    });

    /**
     * 3. FUNGSI UNTUK KONFIRMASI DELETE (SweetAlert)
     * Dipanggil oleh tombol "Delete"
     */
    function deleteSubModul(id) {
        Swal.fire({
            title: '{{ __("admin.swal.delete_title") }}',
            text: "{{ __("admin.swal.delete_text") }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '{{ __("admin.swal.delete_confirm") }}',
            cancelButtonText: '{{ __("admin.swal.cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                // Cari form tersembunyi dan submit
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endpush
