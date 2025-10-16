<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('admin.user.upload') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Upload Data Pendaftar</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="file" name="file" class="form-control" required>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Import</button>
        </div>
      </div>
    </form>
  </div>
</div>
