<div class="modal fade" id="editQuestionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl"> {{-- [DIPERBARUI] Ubah ke XL --}}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('admin.reflection_modal.edit_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="editQuestionForm">
                    @csrf
                    @method('PUT')

                    {{-- [BARU] Tipe Pertanyaan --}}
                    <div class="mb-3">
                        <label for="edit_question_type" class="form-label">Tipe Pertanyaan</label>
                        <select name="type" id="edit_question_type" class="form-select">
                            <option value="essay">Esai (Jawaban Teks)</option>
                            <option value="multiple_choice">Pilihan Ganda</option>
                        </select>
                    </div>

                    {{-- Tabs Bahasa (Tidak Berubah) --}}
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="q-id-tab-edit" data-bs-toggle="tab" data-bs-target="#q-id-pane-edit" type="button" role="tab">ID</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="q-en-tab-edit" data-bs-toggle="tab" data-bs-target="#q-en-pane-edit" type="button" role="tab">EN</button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="q-id-pane-edit" role="tabpanel">
                            <div class="mb-3">
                                <label class="form-label">{{ __('admin.reflection_modal.question_text_id') }}</label>
                                <textarea id="edit_question_text_id" name="question_text[id]" class="form-control rich-text-editor" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="q-en-pane-edit" role="tabpanel">
                            <div class="mb-3">
                                <label class="form-label">{{ __('admin.reflection_modal.question_text_en') }}</label>
                                <textarea id="edit_question_text_en" name="question_text[en]" class="form-control rich-text-editor" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- [BARU] Container Pilihan Ganda (Dikelola JS) --}}
                    <div id="edit_mc_options_container" style="display: none;">
                        <h6 class="text-primary">Opsi Pilihan Ganda</h6>
                        <p class="text-muted small">Tulis opsi jawaban di bawah ini dan pilih satu jawaban yang benar.</p>

                        {{-- Daftar opsi akan diisi oleh 'editButton()' JS --}}
                        <div id="edit_options_list">
                        </div>

                        <button type="button" id="edit_add_option_btn" class="btn btn-outline-primary btn-sm mt-2">
                            <i class="fa fa-plus"></i> Tambah Opsi
                        </button>
                    </div>

                    <hr>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('admin.reflection_modal.order_label') }}</label>
                        <input type="number" id="edit_question_order" name="order" class="form-control" value="1">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.submodul_modal.close') }}</button>
                <button type="submit" form="editQuestionForm" class="btn btn-primary">{{ __('admin.submodul_modal.save_changes') }}</button>
            </div>
        </div>
    </div>
</div>
