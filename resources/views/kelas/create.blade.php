<div class="modal fade" id="createModalKelas" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">
                    {{-- [DIUBAH] --}}
                    {{ __('admin.kelas_modal.add_title') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('kelas.store') }}" method="post" id="storeFormKelas">
                @csrf
                <div class="modal-body">
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            {{-- [DIUBAH] --}}
                            <label for="modul_id" class="form-label">{{ __('admin.kelas_modal.select_module') }}</label>
                            <select name="modul_id" id="modul_id" class="form-control"
                                style="width: 100%; border-color:darkgrey">
                                {{-- [DIUBAH] --}}
                                <option value="" disabled {{ old('modul_id') ? '' : 'selected' }}>{{ __('admin.kelas_modal.module_placeholder') }}</option>
                                @foreach ($modul as $m)
                                    <option value="{{ $m->id }}" {{ (string) old('modul_id') === (string) $m->id ? 'selected' : '' }}>
                                        {{-- [PERBAIKAN KRITIS] Menggunakan Spatie, bukan kolom _id/_en --}}
                                        {{ $m->judul }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <ul class="nav nav-tabs mb-3" id="kelasLangTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="kelas-id-tab" data-bs-toggle="tab" data-bs-target="#kelas-id-pane" type="button" role="tab">
                                Indonesia (ID)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="kelas-en-tab" data-bs-toggle="tab" data-bs-target="#kelas-en-pane" type="button" role="tab">
                                English (EN)
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="kelasLangTabsContent">
                        {{-- Tab Bahasa Indonesia --}}
                        <div class="tab-pane fade show active" id="kelas-id-pane" role="tabpanel">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    {{-- [DIUBAH] --}}
                                    <label for="nama_kelas_id" class="form-label">{{ __('admin.kelas_modal.class_name_id') }}</label>
                                    {{-- Perhatikan name="nama_kelas[id]" --}}
                                    <input type="text" name="nama_kelas[id]" id="nama_kelas_id" required class="form-control" style="border-color:darkgrey">
                                </div>
                            </div>
                        </div>
                        {{-- Tab Bahasa Inggris --}}
                        <div class="tab-pane fade" id="kelas-en-pane" role="tabpanel">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    {{-- [DIUBAH] --}}
                                    <label for="nama_kelas_en" class="form-label">{{ __('admin.kelas_modal.class_name_en') }}</label>
                                    {{-- Perhatikan name="nama_kelas[en]" --}}
                                    <input type="text" name="nama_kelas[en]" id="nama_kelas_en" required class="form-control" style="border-color:darkgrey">
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($isAdmin)
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                {{-- [DIUBAH] --}}
                                <label for="guru_id" class="form-label">{{ __('admin.kelas_modal.select_teacher') }}</label>
                                <select name="guru_id" id="guru_id" class="form-control"
                                    style="width: 100%; border-color:darkgrey">

                                </select>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="modal-footer">
                    {{-- [DIUBAH] --}}
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.kelas_modal.close') }}</button>
                    <button type="button" id="btnCreateKelas" class="btn btn-primary">{{ __('admin.kelas_modal.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
