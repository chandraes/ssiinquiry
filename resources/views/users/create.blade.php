<div class="modal fade" id="createModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">
                    Tambah Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('user.store')}}" method="post" id="storeForm">
                @csrf

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input name="name" id="name" required class="form-control">
                            </input>
                        </div>
                        <div class="col-md-12 mb-3">
                        <label for="role_id" class="form-label">Role</label>
                            <select name="role_id" id="role_id" class="form-select" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="username" class="form-label">Nama Pengguna</label>
                            <input type="text" name="username" id="username" required class="form-control">
                            </input>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" required class="form-control">
                            </input>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="password" class="form-label">Kata Sandi</label>
                            <input type="password" name="password" id="password" required class="form-control">
                            </input>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required class="form-control">
                            </input>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

