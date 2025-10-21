<!-- Modal Upload Peserta -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('kelas.peserta.upload') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
        @csrf
        <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title" id="uploadModalLabel">Upload Peserta Kelas (Excel)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">

          <div class="alert alert-info">
            Format file harus memiliki kolom: <b>nama_kelas</b> dan <b>nama_siswa</b><br>
            Unduh template terlebih dahulu untuk memastikan format sesuai.
          </div>

          <div class="form-group mb-3">
            <label for="file" class="form-label">Pilih File (.xlsx / .csv)</label>
            <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls,.csv" required>
          </div>

        </div>
        <div class="modal-footer justify-content-between">
          <a href="{{ route('kelas.peserta.download-template') }}" class="btn btn-success">
            <i class="fa fa-download me-1"></i> Download Template
          </a>
          <div>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-info" id="btnUpload">
              <i class="fa fa-upload me-1"></i> Upload
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
