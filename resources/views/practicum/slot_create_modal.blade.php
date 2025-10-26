<div class="modal fade" id="addSlotModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">


            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.practicum.add_slot') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                <form action="{{ route('practicum_slot.store') }}" method="POST" id="formPracticumSlotStore">
                            @csrf
                            <input type="hidden" name="sub_module_id" value="{{ $subModul->id }}">
                    <label class="form-label fw-bold">{{ __('admin.practicum.slot_label') }}</label>
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item"><button class="nav-link active" id="slot-l-id-tab-c" data-bs-toggle="tab" data-bs-target="#slot-l-id-pane-c" type="button" role="tab">ID</button></li>
                        <li class="nav-item"><button class="nav-link" id="slot-l-en-tab-c" data-bs-toggle="tab" data-bs-target="#slot-l-en-pane-c" type="button" role="tab">EN</button></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="slot-l-id-pane-c" role="tabpanel">
                            <input name="label[id]" class="form-control" required placeholder="Contoh: Data SPL Tenang">
                        </div>
                        <div class="tab-pane fade" id="slot-l-en-pane-c" role="tabpanel">
                            <input name="label[en]" class="form-control" required placeholder="Example: SPL Quiet Data">
                        </div>
                    </div>

                    <hr>

                    <label class="form-label fw-bold">{{ __('admin.practicum.slot_desc') }}</label>
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item"><button class="nav-link active" id="slot-d-id-tab-c" data-bs-toggle="tab" data-bs-target="#slot-d-id-pane-c" type="button" role="tab">ID</button></li>
                        <li class="nav-item"><button class="nav-link" id="slot-d-en-tab-c" data-bs-toggle="tab" data-bs-target="#slot-d-en-pane-c" type="button" role="tab">EN</button></li>
                    </ul>
                    <div class="mb-3">
                        <label for="phyphox_experiment_type" class="form-label">Tipe Data Phyphox</label>
                        <select name="phyphox_experiment_type" class="form-control" required>
                            <option value="">-- Pilih Tipe Data --</option>
                            <option value="audio_amplitude">Audio Amplitude (Amplitudo vs Waktu)</option>
                            <option value="audio_spectrum">Audio Spectrum (Spektrum vs Frekuensi)</option>
                            {{-- Tambahkan tipe lain di sini nanti --}}
                        </select>
                        <small class="text-muted">Ini akan menentukan bagaimana grafik ditampilkan.</small>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="slot-d-id-pane-c" role="tabpanel">
                            <input name="description[id]" class="form-control" placeholder="Contoh: SPL_Tenang_NamaAnda.csv">
                        </div>
                        <div class="tab-pane fade" id="slot-d-en-pane-c" role="tabpanel">
                            <input name="description[en]" class="form-control" placeholder="Example: SPL_Quiet_YourName.csv">
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-8">
                            <label class="form-label">{{ __('admin.practicum.slot_group') }}</label>
                            <input type="text" name="experiment_group" class="form-control" placeholder="Contoh: Eksperimen 1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('admin.reflection_modal.order_label') }}</label>
                            <input type="number" name="order" class="form-control" value="1">
                        </div>
                    </div>
                     </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.submodul_modal.close') }}</button>
                    <button type="submit" class="btn btn-primary" form="formPracticumSlotStore">{{ __('admin.submodul_modal.save') }}</button>
                </div>
            </div>

    </div>
</div>
