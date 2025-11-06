
<div class="modal fade" id="addArticleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('admin.material_modal.add_article') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{-- TIDAK perlu enctype="multipart/form-data" --}}
                <form action="{{ route('learning_material.store') }}" method="POST" id="storeFormArticle">
                    @csrf
                    <input type="hidden" name="sub_module_id" value="{{ $subModul->id }}">
                    <input type="hidden" name="type" value="article">

                    @include('learning_material.partials.title_tabs', ['id_suffix' => 'article'])

                    <hr>

                    <div class="col-md-12 mb-3">
                        <label for="article_url" class="form-label">{{ __('admin.material_modal.url_label') }}</label>
                        {{-- Input name="content_url" (sesuai controller) --}}
                        <input type="url" name="content_url" id="article_url" class="form-control" placeholder="{{ __('admin.material_modal.url_placeholder') }}" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.submodul_modal.close') }}</button>
                <button type="submit" form="storeFormArticle" class="btn btn-primary">{{ __('admin.submodul_modal.save') }}</button>
            </div>
        </div>
    </div>
</div>
