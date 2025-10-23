<div class="modal fade" id="createQuestionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="{{ route('reflection_question.store') }}" method="POST">
            @csrf
            <input type="hidden" name="sub_module_id" value="{{ $subModul->id }}">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.reflection_modal.add_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="q-id-tab-create" data-bs-toggle="tab" data-bs-target="#q-id-pane-create" type="button" role="tab">ID</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="q-en-tab-create" data-bs-toggle="tab" data-bs-target="#q-en-pane-create" type="button" role="tab">EN</button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="q-id-pane-create" role="tabpanel">
                            <div class="mb-3">
                                <label class="form-label">{{ __('admin.reflection_modal.question_text_id') }}</label>
                                <textarea name="question_text[id]" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="q-en-pane-create" role="tabpanel">
                             <div class="mb-3">
                                <label class="form-label">{{ __('admin.reflection_modal.question_text_en') }}</label>
                                <textarea name="question_text[en]" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('admin.reflection_modal.order_label') }}</label>
                        <input type="number" name="order" class="form-control" value="1">
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
