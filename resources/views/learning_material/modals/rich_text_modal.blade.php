<div class="modal fade" id="addRichTextModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg"> {{-- Dibuat lebih besar (modal-lg) --}}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('admin.material_modal.add_rich_text') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('learning_material.store') }}" method="POST" id="storeFormRichText">
                    @csrf
                    <input type="hidden" name="sub_module_id" value="{{ $subModul->id }}">
                    <input type="hidden" name="type" value="rich_text">
                    <label class="form-label fw-bold">Judul Materi</label>
                    @include('learning_material.partials.title_tabs', ['id_suffix' => 'richtext'])

                    <hr>

                    <label class="form-label fw-bold">Konten Teks</label>
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="content-id-tab-rt" data-bs-toggle="tab"
                                data-bs-target="#content-id-pane-rt" type="button" role="tab">ID</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="content-en-tab-rt" data-bs-toggle="tab"
                                data-bs-target="#content-en-pane-rt" type="button" role="tab">EN</button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="content-id-pane-rt" role="tabpanel">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">{{ __('admin.material_modal.rich_text_content_id') }}</label>
                                {{-- Nama input: content_rich_text[id] --}}
                                {{--
                                SARAN: Tambahkan ID atau kelas unik di sini
                                (misal: class="wysiwyg-editor")
                                untuk di-target oleh JavaScript TinyMCE/CKEditor Anda.
                                --}}
                                <textarea name="content_rich_text[id]" class="form-control rich-text-editor"
                                    rows="10"></textarea>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="content-en-pane-rt" role="tabpanel">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">{{ __('admin.material_modal.rich_text_content_en') }}</label>
                                {{-- Nama input: content_rich_text[en] --}}
                                <textarea name="content_rich_text[en]" class="form-control rich-text-editor"
                                    rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{
                    __('admin.submodul_modal.close') }}</button>
                <button type="submit" form="storeFormRichText" class="btn btn-primary">{{
                    __('admin.submodul_modal.save') }}</button>
            </div>
        </div>

    </div>
</div>
