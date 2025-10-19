<div class="modal fade" id="createModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">
                    Tambah Data Modul
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('modul.store') }}" method="post" id="storeForm">
                @csrf
                <div class="modal-body">
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="judul_id" class="form-label">Judul Modul ID</label>
                            <input name="judul_id" id="judul_id" required class="form-control"
                                style="border-color:darkgrey">
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="judul_en" class="form-label">Judul Modul EN</label>
                            <input type="text" name="judul_en" id="judul_en" required class="form-control"
                                style="border-color:darkgrey">
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="deskripsi_id" class="form-label">Deskripsi ID</label>
                            <textarea name="deskripsi_id" id="deskripsi_id" class="form-control"
                                style="border-color:darkgrey"></textarea>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="deskripsi_en" class="form-label">Deskripsi EN</label>
                            <textarea name="deskripsi_en" id="deskripsi_en" class="form-control"
                                style="border-color:darkgrey"></textarea>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="owner" class="form-label">Pilih Owner</label>
                            <select name="owner[]" id="owner" class="form-control" multiple="multiple"
                                style="width: 100%; border-color:darkgrey">
                                
                            </select>
                            <small class="text-muted">Kamu bisa memilih lebih dari satu owner</small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" id="btnCreate" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>