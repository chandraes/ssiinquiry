<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">
                    Ubah Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- GANTI ROUTE INI DENGAN ROUTE UPDATE USER ANDA --}}
            <form action="{{ route('admin.user.update', $d->id) }}" method="post" id="updateForm-{{$d->id}}">
                @csrf
                @method('PATCH') 

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="edit_name" class="form-label">Nama</label>
                            <input name="name" id="edit_name" required class="form-control" value="{{$d->name}}">
                            </input>
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label for="edit_username" class="form-label">Nama Pengguna</label>
                            <input type="text" name="username" id="edit_username" required class="form-control" value="{{$d->username}}">
                            </input>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" name="email" id="edit_email" required class="form-control" value="{{$d->email}}">
                            </input>    
                        </div>
                        
                        {{-- PASSWORD TIDAK DIISI OTOMATIS. USER HARUS MENGISI JIKA INGIN MENGGANTI --}}
                        <div class="col-md-12 mb-3">
                            <label for="edit_password" class="form-label">Kata Sandi (Kosongkan jika tidak ingin ganti)</label>
                            <input type="password" name="password" id="edit_password" class="form-control">
                            </input>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="edit_password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                            <input type="password" name="password_confirmation" id="edit_password_confirmation" class="form-control">
                            </input>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
