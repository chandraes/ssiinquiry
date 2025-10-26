<div class="modal fade" id="addVideoModal" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Materi Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('learning_material.store') }}" method="POST">
                @csrf
                <input type="hidden" name="sub_module_id" value="{{ $subModul->id }}">
                <input type="hidden" name="type" value="video">
                
                <div class="modal-body">
                    @include('learning_material.partials.title_tabs')
                    <div class="col-md-12 mb-3">
                        <label for="content_url" class="form-label">URL Video (YouTube/Vimeo)</label>
                        <input type="url" name="content_url" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
