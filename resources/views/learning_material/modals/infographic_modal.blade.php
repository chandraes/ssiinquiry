<div class="modal fade" id="addInfographicModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">

        {{-- TIDAK perlu enctype="multipart/form-data" --}}
        <form action="{{ route('learning_material.store') }}" method="POST">
            @csrf
            <input type="hidden" name="sub_module_id" value="{{ $subModul->id }}">
            <input type="hidden" name="type" value="infographic">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.material_modal.add_infographic') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    {{-- Menggunakan partial 'title_tabs' yang sudah ada --}}
                    @include('learning_material.partials.title_tabs', ['id_suffix' => 'infographic'])

                    <hr>

                    <div class="col-md-12 mb-3">
                        <label for="infographic_url" class="form-label">{{ __('admin.material_modal.url_label') }}</label>
                        {{-- Nama input: "content_url", sesuai controller --}}
                        <input type="url" name="content_url" id="infographic_url" class="form-control" placeholder="{{ __('admin.material_modal.url_placeholder') }}" required>
                        <small class="text-muted">Masukkan URL langsung ke gambar infografis (misal: .../gambar.jpg)</small>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.submodul_modal.close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('admin.submodul_modal.save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
