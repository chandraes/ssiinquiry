@extends('layouts.app')

@section('title')
Edit Profile
@endsection

@section('content')
@include('swal')

<section class="main-content mt-0">
    <div class="row">
        <div class="col-xl-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Edit Profil</div>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="d-flex mb-5 align-items-center">
                            <div class="position-relative mx-2">
                                @if($user->profile && $user->profile->foto)
                                    <img style="width: 300px; height: 300px;" src="{{ asset('storage/' . $user->profile->foto) }}"
                                    class="avatar-xl"
                                    alt="Foto Profil">
                                @else
                                    <img style="width: 300px; height: 300px;" src="{{ asset('assets/images/users/default.jpg') }}" class="avatar-xl" alt="Default Foto">
                                @endif
                            </div>
                            <div class="ms-auto mt-xl-2 mt-lg-0 me-lg-2">
                                <label class="btn btn-primary btn-lg mt-1 mb-1">
                                    <i class="fe fe-camera me-1"></i> Ganti Foto
                                    <input type="file" name="foto" class="d-none" accept="image/*" onchange="previewImage(event)">
                                </label>

                                @if($user->profile && $user->profile->foto)
                                    <a href="javascript:void(0);"
                                        class="btn btn-danger btn-lg mt-1 mb-1"
                                        id="hapusFotoBtn"
                                        data-url="{{ route('profile.delete-foto') }}">
                                        <i class="fe fe-camera-off me-1 float-start"></i>Hapus Foto
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Asal Sekolah</label>
                            <input
                                type="text"
                                class="form-control"
                                name="asal_sekolah"
                                value="{{ old('asal_sekolah', $user->profile->asal_sekolah ?? '') }}"
                                placeholder="Masukkan asal sekolah">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Nomor HP</label>
                            <input
                                type="text"
                                class="form-control"
                                name="nomor_hp"
                                value="{{ old('nomor_hp', $user->profile->nomor_hp ?? '') }}"
                                placeholder="Masukkan nomor HP">
                        </div>

                        <hr class="my-4">

                        <div class="form-group mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password baru">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-lg btn-primary">Simpan Perubahan</button>
                        <a href="{{ url()->previous() }}" class="btn btn-lg btn-danger">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js')
<script src="{{ asset('assets/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('preview-foto');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

document.getElementById('hapusFotoBtn')?.addEventListener('click', function () {
    const url = this.dataset.url;

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
            window.location.href = url;
        }
    });
});
</script>
@endpush
