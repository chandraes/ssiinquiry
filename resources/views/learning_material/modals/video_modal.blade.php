<div class="modal fade" id="addVideoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        
            <input type="hidden" name="sub_module_id" value="{{ $subModul->id }}">
            <input type="hidden" name="type" value="video">

            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">{{__('admin.material_modal.add_video') }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                
                    <div class="modal-body">
                        <form action="{{ route('learning_material.store') }}" method="POST" id="storeFormVideo">
                            @csrf
                            <input type="hidden" name="sub_module_id" value="{{ $subModul->id }}">
                            <input type="hidden" name="type" value="article">

                            @include('learning_material.partials.title_tabs')

                            <div class="col-md-12 mb-3">
                                <label for="content_url" class="form-label">{{__('admin.material_modal.url_video_label') }}</label>
                                <input type="url" name="content_url" class="form-control" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('admin.submodul_modal.close') }}</button>
                        <button type="submit" form="storeFormVideo" class="btn btn-primary">{{__('admin.submodul_modal.save') }}</button>
                    </div>
                
            </div>
        
    </div>
</div>
