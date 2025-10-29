<div class="modal fade" id="createModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">
                    {{__('admin.modul.create.header')}}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>


                <div class="modal-body">
                      <form action="{{ route('modul.store') }}" method="post" id="storeForm" enctype="multipart/form-data">
                @csrf
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="phyphox_id" class="form-label">{{__('admin.modul.create.input_tool_phyphox')}}</label>
                            {{-- <select name="phyphox_id[]" id="phyphox_id" class="form-control" multiple="multiple"
                                style="width: 100%; border-color:darkgrey"> --}}
                            <select name="phyphox_id[]" id="phyphox_id" class="form-control form-select select2" multiple="multiple"
                            data-bs-placeholder="Select" style="width: 100%; border-color:darkgrey">
                                {{-- 💡 TAMBAHKAN OPSI KOSONG INI --}}
                                @foreach ($phyphox as $p)
                                    <option value="{{ $p->id }}" {{ in_array($p->id, (array) old('phyphox_id', [])) ?
                                        'selected' : '' }}>
                                        {{ $p->kategori }} ({{ $p->nama }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{__('admin.modul.create.instructions_tool')}}</small>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="image" class="form-label">{{__('admin.modul.create.input_image')}}</label>
                            <input type="file" name="image" id="image" class="form-control"
                                style="border-color:darkgrey" accept="image/png, image/jpeg">
                            <small class="text-muted">Max 2MB. Format: .jpg, .png</small>
                        </div>
                    </div>
                    <hr>
                    <ul class="nav nav-tabs mb-3" id="modulLangTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="modul-id-tab" data-bs-toggle="tab"
                                data-bs-target="#modul-id-pane" type="button" role="tab">
                                Indonesia (ID)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="modul-en-tab" data-bs-toggle="tab"
                                data-bs-target="#modul-en-pane" type="button" role="tab">
                                English (EN)
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="modulLangTabsContent">

                        {{-- Tab Bahasa Indonesia --}}
                        <div class="tab-pane fade show active" id="modul-id-pane" role="tabpanel">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="judul_id" class="form-label">{{__('admin.modul.create.input_titile')}} (ID)</label>
                                    {{-- Perhatikan name="judul[id]" --}}
                                    <input name="judul[id]" id="judul_id" required class="form-control"
                                        style="border-color:darkgrey">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="deskripsi_id" class="form-label">{{__('admin.modul.create.inout_description')}} (ID)</label>
                                    {{-- Perhatikan name="deskripsi[id]" --}}
                                    <textarea name="deskripsi[id]" id="deskripsi_id" class="form-control"
                                        style="border-color:darkgrey"></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Tab Bahasa Inggris --}}
                        <div class="tab-pane fade" id="modul-en-pane" role="tabpanel">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="judul_en" class="form-label">{{__('admin.modul.create.input_titile')}} (EN)</label>
                                    {{-- Perhatikan name="judul[en]" --}}
                                    <input type="text" name="judul[en]" id="judul_en" required class="form-control"
                                        style="border-color:darkgrey">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="deskripsi_en" class="form-label">{{__('admin.modul.create.inout_description')}} (EN)</label>
                                    {{-- Perhatikan name="deskripsi[en]" --}}
                                    <textarea name="deskripsi[en]" id="deskripsi_en" class="form-control"
                                        style="border-color:darkgrey"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('admin.button.close')}}</button>
                    <button type="submit" form="storeForm" id="btnCreate" class="btn btn-primary">{{__('admin.button.save')}}</button>
                </div>

        </div>
    </div>
</div>
