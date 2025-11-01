@extends('layouts.app')

@section('title')
    {{ __('admin.practicum.title') }}: {{ $subModul->title }}
@endsection

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="card-title">{{ $subModul->title }}</h2>
            <p class="text-muted">{{ $subModul->description }}</p>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">{{ __('admin.practicum.instructions') }}</h5>
            </div>
            <div>
                @php
                    $instruction = $subModul->learningMaterials->where('type', 'rich_text')->first();
                @endphp
            </div>
        </div>

        <div class="card-body">
            @if($instruction)
                <div class="rich-text-content border p-3 rounded-2">
                    {!! $instruction->content !!}
                </div>
            @else
                <div class="alert alert-info text-center">
                    {{ __('admin.siswa.no_practicum_instruction') }}.
                </div>
            @endif
        </div>
    </div>

    {{-- ======== BAGIAN SLOT UNGGAHAN ========= --}}
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">{{ __('admin.practicum.upload_slots') }}</h5>
            </div>
        </div>

        <div class="card-body">
            <div class="list-group">
                @forelse($subModul->practicumUploadSlots as $slot)
                    <div class="list-group-item mb-3 p-3 border">
                        <div class="mb-2">
                            <strong>{{ $slot->label }}</strong><br>
                            <small class="text-muted">
                                {{ $slot->experiment_group ? 'Grup: ' . $slot->experiment_group . ' | ' : '' }}
                                {{ $slot->description ? 'File: ' . $slot->description : '' }}
                            </small>
                        </div>

                        {{-- [BARU] Form Upload File CSV --}}
                        <form action="{{ route('practicum_slot.upload_csv', $slot->id) }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center gap-2">
                            @csrf
                            <input type="file" name="csv_file" accept=".csv" class="form-control form-control-sm" required>
                            <button type="submit" class="btn btn-success btn-sm pe-3">
                                <i class="fa fa-upload me-1"></i> {{ __('admin.siswa.upload_csv') }}
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="alert alert-info text-center">
                        {{__('admin.siswa.no_slot')}}.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Konfirmasi sebelum upload
    document.querySelectorAll('.upload-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: '{{__("admin.swal.upload.title}}',
                text: '{{__("admin.swal.upload.text}}',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '{{__("admin.swal.upload.confirm}}',
                cancelButtonText: '{{__("admin.button.cancel}}'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // SweetAlert setelah upload berhasil
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '{{__("admin.swal.success.title}}',
            text: "{{ session('success') }}",
            confirmButtonText: '{{__("admin.swal.success.confirm}}'
        });
    @endif
});
</script>
@endpush
