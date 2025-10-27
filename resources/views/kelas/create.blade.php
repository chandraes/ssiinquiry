{{--
File: resources/views/kelas/create.blade.php
[DIPERBAIKI] Modal ini sekarang memiliki ID statis dan hidden input
--}}

<div class="modal fade" id="createModalKelas" tabindex="-1" role="dialog" aria-labelledby="createModalKelasLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalKelasLabel">
                    Tambah Kelas Baru untuk:
                    <strong id="createModalKelasJudul" class="text-primary">...</strong>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form id="storeFormKelas" action="{{ route('kelas.store') }}" method="POST">
                    @csrf

                    {{-- [BARU] Hidden input untuk Modul ID, diisi oleh JS --}}
                    <input type="hidden" name="modul_id" id="modul_id_for_kelas" value="">

                    {{-- Nama Kelas (ID) --}}
                    <div class="form-group mb-3">
                        <label for="nama_kelas_id" class="form-label">Nama Kelas (Bahasa Indonesia)</label>
                        <input type="text" class="form-control" id="nama_kelas_id" name="nama_kelas[id]" required>
                    </div>

                    {{-- Nama Kelas (EN) --}}
                    <div class="form-group mb-3">
                        <label for="nama_kelas_en" class="form-label">Nama Kelas (English)</label>
                        <input type="text" class="form-control" id="nama_kelas_en" name="nama_kelas[en]" required>
                    </div>

                    {{-- Guru Pengajar (Select2) --}}
                    <div class="form-group mb-3">
                        <label for="guru_id" class="form-label">Guru Pengajar (Owner)</label>
                        <select class="form-control" id="guru_id" name="owner" style="width: 100%;">
                            {{-- Select2 akan mengisi ini --}}
                        </select>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnCreateKelas">Simpan</button>
            </div>
        </div>
    </div>
</div>
