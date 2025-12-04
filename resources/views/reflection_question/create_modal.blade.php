<div class="modal fade" id="createQuestionModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="createQuestionModalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('admin.reflection_modal.add_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('reflection_question.store') }}" method="POST" id="reflectionQuestionStore">
                    @csrf
                    <input type="hidden" name="sub_module_id" value="{{ $subModul->id }}">

                    {{-- [BARU] Tipe Pertanyaan --}}
                    <div class="mb-3">
                        <label for="create_question_type" class="form-label">Tipe Pertanyaan</label>
                        <select name="type" id="create_question_type" class="form-select">
                            <option value="essay" selected>Esai (Jawaban Teks)</option>
                            <option value="multiple_choice">Pilihan Ganda</option>
                        </select>
                    </div>

                    {{-- Tabs Bahasa (Tidak Berubah) --}}
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="q-id-tab-create" data-bs-toggle="tab"
                                data-bs-target="#q-id-pane-create" type="button" role="tab">ID</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="q-en-tab-create" data-bs-toggle="tab"
                                data-bs-target="#q-en-pane-create" type="button" role="tab">EN</button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="q-id-pane-create" role="tabpanel">
                            <div class="mb-3">
                                <label class="form-label">{{ __('admin.reflection_modal.question_text_id') }}</label>
                                <textarea name="question_text[id]" class="form-control rich-text-editor" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="q-en-pane-create" role="tabpanel">
                            <div class="mb-3">
                                <label class="form-label">{{ __('admin.reflection_modal.question_text_en') }}</label>
                                <textarea name="question_text[en]" class="form-control rich-text-editor" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- [BARU] Container Pilihan Ganda (Dikelola JS) --}}
                    <div id="create_mc_options_container" style="display: none;">
                        <h6 class="text-primary">Opsi Pilihan Ganda</h6>
                        <p class="text-muted small">Tulis opsi jawaban di bawah ini dan pilih satu jawaban yang benar.</p>

                        {{-- Template untuk opsi akan ditambahkan di sini oleh JS --}}
                        <div id="create_options_list">
                            {{-- Contoh (opsi pertama akan ditambah JS) --}}
                        </div>

                        <button type="button" id="create_add_option_btn" class="btn btn-outline-primary btn-sm mt-2">
                            <i class="fa fa-plus"></i> Tambah Opsi
                        </button>
                    </div>

                    <hr>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('admin.reflection_modal.order_label') }}</label>
                        <input type="number" name="order" class="form-control" value="1">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{
                    __('admin.submodul_modal.close') }}</button>
                <button type="submit" class="btn btn-primary" form="reflectionQuestionStore">{{
                    __('admin.submodul_modal.save') }}</button>
            </div>
        </div>
    </div>
</div>
