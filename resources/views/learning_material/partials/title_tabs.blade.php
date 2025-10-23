{{-- Ini adalah partial untuk input title ID/EN --}}
<ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="id-tab-{{ $id_suffix ?? 'create' }}" data-bs-toggle="tab" data-bs-target="#id-pane-{{ $id_suffix ?? 'create' }}" type="button" role="tab">ID</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="en-tab-{{ $id_suffix ?? 'create' }}" data-bs-toggle="tab" data-bs-target="#en-pane-{{ $id_suffix ?? 'create' }}" type="button" role="tab">EN</button>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane fade show active" id="id-pane-{{ $id_suffix ?? 'create' }}" role="tabpanel">
        <div class="col-md-12 mb-3">
            <label class="form-label">Judul (ID)</label>
            <input name="title[id]" required class="form-control">
        </div>
    </div>
    <div class="tab-pane fade" id="en-pane-{{ $id_suffix ?? 'create' }}" role="tabpanel">
         <div class="col-md-12 mb-3">
            <label class="form-label">Title (EN)</label>
            <input type="text" name="title[en]" required class="form-control">
        </div>
    </div>
</div>
