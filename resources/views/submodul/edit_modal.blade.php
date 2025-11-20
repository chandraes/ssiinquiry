{{--
File: resources/views/submodul/edit_modal.blade.php
(Dibersihkan dari Pengaturan Forum)
--}}
<div class="modal fade" id="editSubModulModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="editSubModulModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSubModulModalLabel">
                    {{ __('admin.submodul_modal.edit_title') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="" method="post" id="editSubModulForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="type" id="edit_submodul_type" value="">

                    {{-- Navigasi Tab Bahasa --}}
                    <ul class="nav nav-tabs mb-3" id="editSubModulLangTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="edit-submodul-id-tab" data-bs-toggle="tab"
                                data-bs-target="#edit-submodul-id-pane" type="button" role="tab">
                                {{ __('admin.submodul_modal.tab_id') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="edit-submodul-en-tab" data-bs-toggle="tab"
                                data-bs-target="#edit-submodul-en-pane" type="button" role="tab">
                                {{ __('admin.submodul_modal.tab_en') }}
                            </button>
                        </li>
                    </ul>

                    {{-- Konten Tab Bahasa --}}
                    <div class="tab-content" id="editSubModulLangTabsContent">
                        {{-- Tab Bahasa Indonesia --}}
                        <div class="tab-pane fade show active" id="edit-submodul-id-pane" role="tabpanel">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="edit_sub_title_id" class="form-label">{{
                                        __('admin.submodul_modal.title_id_label') }}</label>
                                    <input name="title[id]" id="edit_sub_title_id" required class="form-control"
                                        style="border-color:darkgrey" value="">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="edit_sub_description_id" class="form-label">{{
                                        __('admin.submodul_modal.desc_id_label') }}</label>
                                    <textarea name="description[id]" id="edit_sub_description_id" class="form-control rich-text-editor"
                                    rows="10" style="border-color:darkgrey"></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Tab Bahasa Inggris --}}
                        <div class="tab-pane fade" id="edit-submodul-en-pane" role="tabpanel">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="edit_sub_title_en" class="form-label">{{
                                        __('admin.submodul_modal.title_en_label') }}</label>
                                    <input type="text" name="title[en]" id="edit_sub_title_en" required
                                        class="form-control" style="border-color:darkgrey" value="">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="edit_sub_description_en" class="form-label">{{
                                        __('admin.submodul_modal.desc_en_label') }}</label>
                                    <textarea name="description[en]" id="edit_sub_description_en" class="form-control rich-text-editor"
                                    rows="10" style="border-color:darkgrey"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- Field Urutan dan Poin Maksimal --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="edit_sub_order" class="form-label">{{ __('admin.submodul_modal.order_label')
                                    }}</label>
                                <input type="number" name="order" id="edit_sub_order" class="form-control"
                                    style="width: 100px; border-color:darkgrey" value="">
                                <small class="text-muted">{{ __('admin.submodul_modal.order_help') }}</small>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3" id="edit_max_points_wrapper">
                            <div class="form-group">
                                <label for="edit-max-points" class="form-label fw-bold">
                                    Poin Maksimal
                                </label>
                                <input type="number" class="form-control" id="edit-max-points" name="max_points"
                                    value="10" min="0" required style="width: 100px; border-color:darkgrey">
                                <small class="text-muted">
                                    Skor maks untuk sub-modul ini.
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- DIV PENGATURAN FORUM DIHAPUS DARI SINI --}}

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn " data-bs-dismiss="modal">{{ __('admin.submodul_modal.close')
                    }}</button>
                <button type="submit" class="btn btn-primary" form="editSubModulForm">{{
                    __('admin.submodul_modal.save_changes') }}</button>
            </div>

        </div>
    </div>
</div>
