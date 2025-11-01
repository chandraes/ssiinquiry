<div class="modal fade" id="editSlotModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('admin.practicum.edit_slot')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                     <form action="" method="POST" id="editSlotForm">
            @csrf
            @method('PUT')
                    <label class="form-label fw-bold">{{ __('admin.practicum.slot_label') }}</label>
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item"><button class="nav-link active" id="slot-l-id-tab-e" data-bs-toggle="tab"
                                data-bs-target="#slot-l-id-pane-e" type="button" role="tab">ID</button></li>
                        <li class="nav-item"><button class="nav-link" id="slot-l-en-tab-e" data-bs-toggle="tab"
                                data-bs-target="#slot-l-en-pane-e" type="button" role="tab">EN</button></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="slot-l-id-pane-e" role="tabpanel">
                            <input id="edit_slot_label_id" name="label[id]" class="form-control" required>
                        </div>
                        <div class="tab-pane fade" id="slot-l-en-pane-e" role="tabpanel">
                            <input id="edit_slot_label_en" name="label[en]" class="form-control" required>
                        </div>
                    </div>
                    <hr>
                    <label class="form-label fw-bold">{{ __('admin.practicum.slot_desc') }}</label>
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item"><button class="nav-link active" id="slot-d-id-tab-e" data-bs-toggle="tab"
                                data-bs-target="#slot-d-id-pane-e" type="button" role="tab">ID</button></li>
                        <li class="nav-item"><button class="nav-link" id="slot-d-en-tab-e" data-bs-toggle="tab"
                                data-bs-target="#slot-d-en-pane-e" type="button" role="tab">EN</button></li>
                    </ul>

                    <div class="mb-3">
                        <label for="phyphox_experiment_type" class="form-label">{{__('admin.practicum.slot_tool')}}</label>
                        <select name="phyphox_experiment_type" class="form-control" required>
                            <option value="">-- {{__('admin.practicum.choose_data')}} --</option>
                            <option value="audio_amplitude">{{__('admin.practicum.amplitude')}}</option>
                            <option value="audio_spectrum">{{__('admin.practicum.spectrum')}}</option>
                            {{-- Tambahkan tipe lain di sini nanti --}}
                        </select>
                        <small class="text-muted">{{__('admin.practicum.tool_instruction')}}.</small>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="slot-d-id-pane-e" role="tabpanel">
                            <input id="edit_slot_description_id" name="description[id]" class="form-control">
                        </div>
                        <div class="tab-pane fade" id="slot-d-en-pane-e" role="tabpanel">
                            <input id="edit_slot_description_en" name="description[en]" class="form-control">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-8">
                            <label class="form-label">{{ __('admin.practicum.slot_group') }}</label>
                            <input type="text" id="edit_slot_experiment_group" name="experiment_group"
                                class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('admin.reflection_modal.order_label') }}</label>
                            <input type="number" id="edit_slot_order" name="order" class="form-control">
                        </div>
                    </div>
                     </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{
                        __('admin.submodul_modal.close') }}</button>
                    <button type="submit" class="btn btn-primary" form="editSlotForm">{{ __('admin.submodul_modal.save_changes') }}</button>
                </div>
            </div>

    </div>
</div>
