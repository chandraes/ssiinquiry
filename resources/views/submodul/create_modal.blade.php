{{-- File: resources/views/submodul/create_modal.blade.php (DIBERSIHKAN) --}}

<div class="modal fade" id="createSubModulModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="createSubModulModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSubModulModalLabel">
                    {{ __('admin.submodul_modal.add_title') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('submodul.store') }}" method="post" id="storeSubModulForm">
                    @csrf
                    <input type="hidden" name="modul_id" value="{{ $modul->id }}">
                    <input type="hidden" name="type" id="create_submodul_type" value="learning">

                    {{-- Navigasi Tab Bahasa --}}
                    <ul class="nav nav-tabs mb-3" id="subModulLangTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="submodul-id-tab" data-bs-toggle="tab"
                                data-bs-target="#submodul-id-pane" type="button" role="tab">
                                {{ __('admin.submodul_modal.tab_id') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="submodul-en-tab" data-bs-toggle="tab"
                                data-bs-target="#submodul-en-pane" type="button" role="tab">
                                {{ __('admin.submodul_modal.tab_en') }}
                            </button>
                        </li>
                    </ul>

                    {{-- Konten Tab Bahasa --}}
                    <div class="tab-content" id="subModulLangTabsContent">
                        {{-- Tab Bahasa Indonesia --}}
                        <div class="tab-pane fade show active" id="submodul-id-pane" role="tabpanel">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="sub_title_id" class="form-label">{{
                                        __('admin.submodul_modal.title_id_label') }}</label>
                                    <input name="title[id]" id="sub_title_id" required class="form-control"
                                        style="border-color:darkgrey">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="sub_description_id" class="form-label">{{
                                        __('admin.submodul_modal.desc_id_label') }}</label>
                                    <textarea name="description[id]" id="sub_description_id" class="form-control rich-text-editor"
                                    rows="10"></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Tab Bahasa Inggris --}}
                        <div class="tab-pane fade" id="submodul-en-pane" role="tabpanel">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="sub_title_en" class="form-label">{{
                                        __('admin.submodul_modal.title_en_label') }}</label>
                                    <input type="text" name="title[en]" id="sub_title_en" required class="form-control"
                                        style="border-color:darkgrey">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="sub_description_en" class="form-label">{{
                                        __('admin.submodul_modal.desc_en_label') }}</label>
                                    <textarea name="description[en]" id="sub_description_en" class="form-control rich-text-editor"
                                    rows="10"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- Hanya Field Urutan dan Poin Maksimal --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="order" class="form-label">{{ __('admin.submodul_modal.order_label') }}</label>
                                <input type="number" name="order" id="order" value="1" class="form-control"
                                    style="width: 100px; border-color:darkgrey">
                                <small class="text-muted">{{ __('admin.submodul_modal.order_help') }}</small>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3" id="create_max_points_wrapper">
                            <div class="form-group">
                                <label for="create-max-points" class="form-label fw-bold">
                                    Poin Maksimal
                                </label>
                                <input type="number"
                                       class="form-control"
                                       id="create-max-points"
                                       name="max_points"
                                       value="{{ old('max_points', 10) }}"
                                       min="0"
                                       required
                                       style="width: 100px; border-color:darkgrey">
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{
                    __('admin.submodul_modal.close') }}</button>
                <button type="submit" class="btn btn-primary" form="storeSubModulForm">
                    {{ __('admin.submodul_modal.save') }}
                </button>
            </div>
        </div>
    </div>
</div>
