<div class="modal fade" id="editMaterialModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('admin.material_modal.edit_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST" id="editMaterialForm">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <label class="form-label fw-bold">Judul Materi</label>
                    @include('learning_material.partials.title_tabs', ['id_suffix' => 'edit'])
                    <hr>
                    {{-- ID 'edit-url-field' akan digunakan oleh JS --}}
                    <div id="edit-url-field" class="col-md-12 mb-3" style="display: none;">
                        <label for="edit_content_url" class="form-label">{{ __('admin.material_modal.url_label') }}</label>
                        <input type="url" name="content_url" id="edit_content_url" class="form-control" placeholder="{{ __('admin.material_modal.url_placeholder') }}">
                    </div>
                    {{-- ID 'edit-richtext-field' akan digunakan oleh JS --}}
                    <div id="edit-richtext-field" style="display: none;">
                        <label class="form-label fw-bold">Konten Teks</label>
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="edit-rt-id-tab" data-bs-toggle="tab" data-bs-target="#edit-rt-id-pane" type="button" role="tab">ID</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="edit-rt-en-tab" data-bs-toggle="tab" data-bs-target="#edit-rt-en-pane" type="button" role="tab">EN</button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="edit-rt-id-pane" role="tabpanel">
                                <textarea name="content_rich_text[id]" id="edit_content_rich_text_id" class="form-control rich-text-editor" rows="10"></textarea>
                            </div>
                            <div class="tab-pane fade" id="edit-rt-en-pane" role="tabpanel">
                                <textarea name="content_rich_text[en]" id="edit_content_rich_text_en" class="form-control rich-text-editor" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.submodul_modal.close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('admin.submodul_modal.save_changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
