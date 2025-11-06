<div class="modal fade" id="addRegulationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('admin.material_modal.add_regulation') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{-- TIDAK perlu enctype="multipart/form-data" --}}
                <form action="{{ route('learning_material.store') }}" method="POST" id="storeFormRegulation">
                    @csrf
                    <input type="hidden" name="sub_module_id" value="{{ $subModul->id }}">
                    <input type="hidden" name="type" value="regulation">

                    @include('learning_material.partials.title_tabs', ['id_suffix' => 'regulation'])
                    <hr>
                    <div class="col-md-12 mb-3">
                        <label for="regulation_url" class="form-label">{{ __('admin.material_modal.url_label') }}</label>
                        {{-- Input name="content_url" (sesuai controller) --}}
                        <input type="url" name="content_url" id="regulation_url" class="form-control" placeholder="{{ __('admin.material_modal.url_placeholder') }}" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.submodul_modal.close') }}</button>
                <button type="submit" form="storeFormRegulation" class="btn btn-primary">{{ __('admin.submodul_modal.save') }}</button>
            </div>
        </div>
    </div>
</div>
