<div class="modal fade" id="createModalKelas" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">
                    Tambah Data Kelas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('kelas.store') }}" method="post" id="storeFormKelas">
                @csrf
                <div class="modal-body">
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="modul_id" class="form-label">Pilih Modul</label>
                            <select name="modul_id" id="modul_id" class="form-control"
                                style="width: 100%; border-color:darkgrey" placeHolder="Select Modul">
                                <option value="" disabled {{ old('modul_id') ? '' : 'selected' }}>-- Pilih Modul --</option>
                                @foreach ($modul as $m)
                                    <option value="{{ $m->id }}" {{ (string) old('modul_id') === (string) $m->id ? 'selected' : '' }}>
                                        {{ $m->judul_id }} / {{ $m->judul_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="nama_kelas" class="form-label">Nama Kelas</label>
                            <input type="text" name="nama_kelas" id="nama_kelas" required class="form-control"
                                style="border-color:darkgrey">
                        </div>
                    </div>

                    @if ($isAdmin)
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="guru_id" class="form-label">Pilih Guru Pengajar</label>
                                <select name="guru_id" id="guru_id" class="form-control"
                                    style="width: 100%; border-color:darkgrey">
                                    
                                </select>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" id="btnCreateKelas" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>