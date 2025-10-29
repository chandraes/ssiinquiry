<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                {{-- [DIUBAH] --}}
                <h5 class="modal-title" id="editModalLabel">{{ __('admin.kelas_modal.edit_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>           
            <div class="modal-body">
                <form method="POST" id="editForm">
                    @csrf
                    @method('PATCH')
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            {{-- [DIUBAH] --}}
                            <label for="edit_modul_id" class="form-label">{{ __('admin.kelas_modal.select_module') }}</label>
                            <select name="modul_id" id="edit_modul_id" class="form-control"
                                style="width: 100%; border-color:darkgrey">
                                {{-- [DIUBAH] --}}
                                <option value="" disabled>{{ __('admin.kelas_modal.module_placeholder') }}</option>
                                @foreach ($modul as $m)
                                    <option value="{{ $m->id }}">
                                        {{-- [PERBAIKAN KRITIS] --}}
                                        {{ $m->judul }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <ul class="nav nav-tabs mb-3" id="editKelasLangTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="edit-kelas-id-tab" data-bs-toggle="tab" data-bs-target="#edit-kelas-id-pane" type="button" role="tab">
                                Indonesia (ID)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="edit-kelas-en-tab" data-bs-toggle="tab" data-bs-target="#edit-kelas-en-pane" type="button" role="tab">
                                English (EN)
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="editKelasLangTabsContent">
                        <div class="tab-pane fade show active" id="edit-kelas-id-pane" role="tabpanel">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    {{-- [DIUBAH] --}}
                                    <label for="edit_nama_kelas_id" class="form-label">{{ __('admin.kelas_modal.class_name_id') }}</label>
                                    {{-- Perhatikan ID baru: edit_nama_kelas_id --}}
                                    <input type="text" name="nama_kelas[id]" id="edit_nama_kelas_id" required class="form-control" style="border-color:darkgrey">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="edit-kelas-en-pane" role="tabpanel">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    {{-- [DIUBAH] --}}
                                    <label for="edit_nama_kelas_en" class_label="form-label">{{ __('admin.kelas_modal.class_name_en') }}</label>
                                    {{-- Perhatikan ID baru: edit_nama_kelas_en --}}
                                    <input type="text" name="nama_kelas[en]" id="edit_nama_kelas_en" required class="form-control" style="border-color:darkgrey">
                                </div>
                            </div>
                        </div>
                    </div>
                {{-- Anda mungkin perlu menambahkan input 'guru_id' di sini untuk Admin --}}
                </form>
            </div>

            <div class="modal-footer">
                {{-- [DIUBAH] --}}
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.kelas_modal.close') }}</button>
                <button type="button" id="btnUpdate" class="btn btn-primary">{{ __('admin.kelas_modal.save_changes') }}</button>
            </div>
        </div>
    </div>
</div>
