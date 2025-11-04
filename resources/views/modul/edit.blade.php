<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">{{__('admin.modul.edit.header')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                {{-- [PERBAIKAN] Gunakan multipart/form-data untuk upload gambar --}}
                <form method="POST" id="editForm" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH') {{-- Method tetap PATCH --}}

                    {{-- [PERBAIKAN] Tabs untuk Bahasa --}}
                    <ul class="nav nav-tabs mb-3" id="editModulLangTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="edit-modul-id-tab" data-bs-toggle="tab"
                                data-bs-target="#edit-modul-id-pane" type="button" role="tab">
                                Indonesia (ID)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="edit-modul-en-tab" data-bs-toggle="tab"
                                data-bs-target="#edit-modul-en-pane" type="button" role="tab">
                                English (EN)
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="editModulLangTabsContent">
                        {{-- Tab Bahasa Indonesia --}}
                        <div class="tab-pane fade show active" id="edit-modul-id-pane" role="tabpanel">
                            <div class="mb-3">
                                <label for="edit_judul_id" class="form-label">{{__('admin.modul.create.input_title')}} (ID)</label>
                                <input name="judul[id]" id="edit_judul_id" required class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="edit_deskripsi_id" class="form-label">{{__('admin.modul.create.input_description')}} (ID)</label>
                                <textarea name="deskripsi[id]" id="edit_deskripsi_id" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        {{-- Tab Bahasa Inggris --}}
                        <div class="tab-pane fade" id="edit-modul-en-pane" role="tabpanel">
                            <div class="mb-3">
                                <label for="edit_judul_en" class="form-label">{{__('admin.modul.create.input_title')}} (EN)</label>
                                <input type="text" name="judul[en]" id="edit_judul_en" required class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="edit_deskripsi_en" class="form-label">{{__('admin.modul.create.input_description')}} (EN)</label>
                                <textarea name="deskripsi[en]" id="edit_deskripsi_en" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    {{-- [AKHIR PERBAIKAN TABS] --}}

                    <hr> {{-- Pemisah visual --}}

                    {{-- Input Gambar Cover --}}
                    <div class="mb-3">
                        <label for="edit_image" class="form-label">Gambar Cover (Opsional)</label>
                        <input class="form-control" type="file" id="edit_image" name="image" accept="image/*">
                        <small class="text-muted">{{__('admin.modul.edit.image_instruction')}}.</small>
                        <div class="mt-2">
                             <img id="edit_image_preview" src="#" alt="Preview Gambar Baru" class="img-thumbnail me-2" width="150" style="display: none;">
                             <img id="edit_image_current" src="#" alt="Gambar Saat Ini" class="img-thumbnail" width="150" style="display: none;">
                        </div>
                    </div>

                    {{-- Select Phyphox --}}
                    <div class="mb-3">
                        <label for="edit_phyphox_id" class="form-label">{{__('admin.modul.create.input_tool_phyphox')}}</label>
                        {{-- [PERBAIKAN] Pastikan ID unik dan ada class untuk JS --}}
                        <select name="phyphox_id[]" id="edit_phyphox_id" class="form-control select2-phyphox-edit" multiple="multiple" style="width: 100%;">
                            {{-- Opsi statis dari $phyphox di index --}}
                             @if(isset($phyphox))
                                @foreach ($phyphox as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }} ({{ $item->kategori }})</option>
                                @endforeach
                             @endif
                        </select>
                         <small class="text-muted">{{__('admin.modul.create.instructions_tool')}}</small>
                    </div>

                    {{-- Select Owner dihapus --}}

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('admin.button.close')}}</button>
                {{-- [PERBAIKAN] Ganti type jadi button agar bisa dicegah JS --}}
                <button type="button" id="btnUpdate" class="btn btn-primary">{{__('admin.button.save_changes')}}</button>
            </div>
        </div>
    </div>
</div>
