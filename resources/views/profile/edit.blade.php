@extends('layouts.app')

@section('title')
{{__('admin.profile.edit.title')}}
@endsection

@section('content')
@include('swal')

<section class="main-content mt-0">
    <div class="row">
        <div class="col-md-12 mb-5">
            <a href="{{ route('profile.index') }}" class="btn btn-secondary btn-lg">
                <i class="fa fa-arrow-left me-1"></i> {{ __('admin.button.back') }}
            </a>
        </div>
        <div class="col-xl-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{__('admin.profile.edit.header')}}</div>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileUpdateForm">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="row align-items-center mb-5">
                            <div class="col-lg-3 text-center">
                                <div class="position-relative d-inline-block">
                                    @if($user->profile && $user->profile->foto)
                                        <img style="width: 200px; height: 200px; object-fit: cover;"
                                             src="{{ asset('storage/' . $user->profile->foto) }}"
                                             class="rounded-circle shadow"
                                             alt="Foto Profil" id="preview-foto">
                                    @else
                                        <img style="width: 200px; height: 200px; object-fit: cover;"
                                             src="{{ asset('assets/images/users/default.jpg') }}"
                                             class="rounded-circle shadow"
                                             alt="Default Foto" id="preview-foto">
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-9 mt-4 mt-lg-0">
                                <label class="btn btn-primary btn-lg mt-1 mb-1">
                                    <i class="fe fe-camera me-1"></i> {{__('admin.profile.edit.change_image')}}
                                    <input type="file" name="foto" class="d-none" accept="image/*" onchange="previewImage(event)">
                                </label>

                                @if($user->profile && $user->profile->foto)
                                    <a href="javascript:void(0);"
                                        class="btn btn-danger btn-lg mt-1 mb-1"
                                        id="hapusFotoBtn">
                                        <i class="fe fe-camera-off me-1"></i> {{__('admin.profile.edit.delete_image')}}
                                    </a>
                                @endif
                                <p class="text-muted mt-2">{{__('admin.profile.edit.max_image')}}.</p>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="name" value="{{ $user->name }}" disabled>
                            <label for="name">{{__('admin.profile.name')}}</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="asal_sekolah"
                                name="asal_sekolah"
                                value="{{ old('asal_sekolah', $user->profile->asal_sekolah ?? '') }}"
                                placeholder="{{__('admin.profile.edit.school_placeholder')}}">
                            <label for="asal_sekolah">{{__('admin.profile.school')}}</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="nomor_hp"
                                name="nomor_hp"
                                value="{{ old('nomor_hp', $user->profile->nomor_hp ?? '') }}"
                                placeholder="{{__('admin.profile.edit.hp_placeholder')}}">
                            <label for="nomor_hp">{{__('admin.profile.hp')}}</label>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">{{__('admin.profile.edit.change_password')}}</h5>
                        <p class="text-muted">{{__('admin.profile.edit.password_instruction')}}.</p>

                        <div class="form-floating mb-3">
                            <input type="password" name="password" class="form-control" id="password" placeholder="{__('admin.profile.edit.new_password_placeholder')}}">
                            <label for="password">{{__('admin.profile.edit.new_password')}}</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="{{__('admin.profile.edit.confirmation_password_placeholder')}}">
                            <label for="password_confirmation">{{__('admin.profile.edit.password_confirmation')}}</label>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <a href="{{ url()->previous() }}" class="btn btn-lg btn-danger">{{__('admin.button.cancel')}}</a>
                        <button type="submit" class="btn btn-lg btn-primary">{{__('admin.button.save_changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<form id="delete-foto-form" action="{{ route('profile.delete-foto') }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('js')
<script src="{{ asset('assets/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script>
// Fungsi Pratinjau Gambar (Bug sudah diperbaiki dengan 'id' di HTML)
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('preview-foto');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

// PERBAIKAN KEAMANAN: Event listener untuk 'Hapus Foto'
document.getElementById('hapusFotoBtn')?.addEventListener('click', function () {
    Swal.fire({
        title: "Hapus Foto?",
        text: "Apakah kamu yakin ingin menghapus foto profil ini?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit form tersembunyi
            document.getElementById('delete-foto-form').submit();
        }
    });
});


// OPTIMASI: Submit form menggunakan AJAX (Fetch API)
document.getElementById('profileUpdateForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Mencegah submit standar

    const form = e.target;
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    const originalButtonText = submitButton.innerHTML;

    // Tampilkan status loading pada tombol
    submitButton.innerHTML = `
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        Menyimpan...
    `;
    submitButton.disabled = true;

    fetch(form.action, {
        method: 'POST', // Form method spoofing (PUT) akan ditangani oleh '@method('PUT')'
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json', // Meminta respon JSON dari controller
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
             // Jika status code bukan 2xx (misal 422, 500)
             return response.json().then(err => Promise.reject(err));
        }
        return response.json(); // Lanjut jika OK
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Berhasil!',
                text: data.message,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });

            // Jika password diubah, reload halaman setelah 2 detik
            if (formData.get('password')) {
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        } else {
            // Menampilkan error dari controller (jika success: false)
            Swal.fire({
                title: 'Gagal!',
                text: data.message || 'Terjadi kesalahan.',
                icon: 'error',
            });
        }
    })
    .catch(error => {
        // Menangani error validasi (422) atau server error (500)
        let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
        if (error.message) {
             errorMessage = error.message;
        }
        // Jika ada error validasi, Anda bisa meloop 'error.errors' di sini

        Swal.fire({
            title: 'Oops!',
            text: errorMessage,
            icon: 'error'
        });
        console.error('Error:', error);
    })
    .finally(() => {
        // Kembalikan tombol ke kondisi semula
        submitButton.innerHTML = originalButtonText;
        submitButton.disabled = false;
    });
});

</script>
@endpush
