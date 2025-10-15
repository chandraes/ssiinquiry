<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Ubah Data Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" id="editForm">
                @csrf
                @method('PATCH')

                <div class="modal-body">
                    <div class="col-md-12 mb-3">
                        <label for="edit_name" class="form-label">Nama</label>
                        <input type="text" name="name" id="edit_name" required class="form-control">
                    </div>

                    <div class="col-md-12">
                        <label for="edit_role" class="form-label">Role</label>
                        <select name="role_id" id="edit_role" class="form-select" required>
                            <option value="">-- Pilih Role --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class=col-md-12 mb-3">
                        <label for="edit_password" class="form-label">Kata Sandi</label>
                        <input type="password" name="password" id="edit_password" class="form-control" placeholder="Kosongkan jika tidak ingin ganti">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="edit_password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                        <input type="password" name="password_confirmation" id="edit_password_confirmation" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
