<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Ubah Data Modul</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" id="editForm">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="edit_judul_id" class="form-label">Judul Modul ID</label>
                            <input name="judul_id" id="edit_judul_id" required class="form-control" style="border-color:darkgrey">
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="edit_judul_en" class="form-label">Judul Modul EN</label>
                            <input type="text" name="judul_en" id="edit_judul_en" required class="form-control" style="border-color:darkgrey">
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="edit_deskripsi" required class="form-control" style="border-color:darkgrey"></textarea>
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
