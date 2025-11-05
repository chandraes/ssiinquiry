<div class="modal fade" id="addInfographicModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('admin.material_modal.add_infographic') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{-- TIDAK perlu enctype="multipart/form-data" --}}
                <form action="{{ route('learning_material.store') }}" method="POST" id="storeFormInfographic">
                    @csrf
                    <input type="hidden" name="sub_module_id" value="{{ $subModul->id }}">
                    <input type="hidden" name="type" value="infographic">                   

                    {{-- Menggunakan partial 'title_tabs' yang sudah ada --}}
                    @include('learning_material.partials.title_tabs', ['id_suffix' => 'infographic'])

                    <hr>

                    <div class="col-md-12 mb-3">
                        <label for="infographic_url" class="form-label">{{ __('admin.material_modal.url_label') }}</label>
                        {{-- Nama input: "content_url", sesuai controller --}}
                        <input type="url" name="content_url" id="infographic_url" class="form-control" placeholder="{{ __('admin.material_modal.url_placeholder') }}" required>
                        <small class="text-muted">{{ __('admin.material_modal.url_instruction') }}</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.submodul_modal.close') }}</button>
                <button type="submit" form="storeFormInfographic" class="btn btn-primary">{{ __('admin.submodul_modal.save') }}</button>
            </div>
        </div>
    </div>
</div>
