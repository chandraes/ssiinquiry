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
                        <div class="form-group">
                            <label for="edit_name" class="form-label">Nama</label>
                            <input type="text" name="name" id="edit_name" class="form-control" style="border-color: darkgrey">
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="edit_username" class="form-label">Username</label>
                            <input type="text" name="username" id="edit_username" class="form-control" style="border-color: darkgrey">
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control" style="border-color: darkgrey">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="edit_role_id" class="form-label">Role</label>
                            <select class="form-control form-select select2" data-bs-placeholder="Choose One" style="width:100%; border-color: darkgrey" name="role_id" id="edit_role_id">
                                <option label>-- Pilih Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" id="btnUpdate" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
