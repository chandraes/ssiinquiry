
<div class="modal fade" id="createSubModulModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="createSubModulModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSubModulModalLabel">
                    {{-- [DIUBAH] --}}
                    {{ __('admin.submodul_modal.add_title') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Form ini akan dikirim ke SubModulController@store --}}
            <form action="{{ route('submodul.store') }}" method="post" id="storeSubModulForm">
                @csrf

                {{-- [PENTING]
                   Variabel $modul didapat dari halaman modul.show
                   yang meng-@include file ini.
                --}}
                <input type="hidden" name="modul_id" value="{{ $modul->id }}">
 <input type="hidden" name="type" id="create_submodul_type" value="learning">
                <div class="modal-body">
                    <ul class="nav nav-tabs mb-3" id="subModulLangTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="submodul-id-tab" data-bs-toggle="tab" data-bs-target="#submodul-id-pane" type="button" role="tab">
                                {{-- [DIUBAH] --}}
                                {{ __('admin.submodul_modal.tab_id') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="submodul-en-tab" data-bs-toggle="tab" data-bs-target="#submodul-en-pane" type="button" role="tab">
                                {{-- [DIUBAH] --}}
                                {{ __('admin.submodul_modal.tab_en') }}
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="subModulLangTabsContent">

                        {{-- Tab Bahasa Indonesia --}}
                        <div class="tab-pane fade show active" id="submodul-id-pane" role="tabpanel">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    {{-- [DIUBAH] --}}
                                    <label for="sub_title_id" class="form-label">{{ __('admin.submodul_modal.title_id_label') }}</label>
                                    {{-- Menggunakan name="title[id]" --}}
                                    <input name="title[id]" id="sub_title_id" required class="form-control" style="border-color:darkgrey">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    {{-- [DIUBAH] --}}
                                    <label for="sub_description_id" class="form-label">{{ __('admin.submodul_modal.desc_id_label') }}</label>
                                    <textarea name="description[id]" id="sub_description_id" class="form-control" style="border-color:darkgrey"></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Tab Bahasa Inggris --}}
                        <div class="tab-pane fade" id="submodul-en-pane" role="tabpanel">
                             <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    {{-- [DIUBAH] --}}
                                    <label for="sub_title_en" class="form-label">{{ __('admin.submodul_modal.title_en_label') }}</label>
                                    <input type="text" name="title[en]" id="sub_title_en" required class="form-control" style="border-color:darkgrey">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    {{-- [DIUBAH] --}}
                                    <label for="sub_description_en" class="form-label">{{ __('admin.submodul_modal.desc_en_label') }}</label>
                                    <textarea name="description[en]" id="sub_description_en" class="form-control" style="border-color:darkgrey"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            {{-- [DIUBAH] --}}
                            <label for="order" class="form-label">{{ __('admin.submodul_modal.order_label') }}</label>
                            <input type="number" name="order" id="order" value="1" class="form-control" style="width: 100px; border-color:darkgrey">
                            <small class="text-muted">{{ __('admin.submodul_modal.order_help') }}</small>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    {{-- [DIUBAH] --}}
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.submodul_modal.close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('admin.submodul_modal.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
