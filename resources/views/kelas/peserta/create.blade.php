<!-- Modal Tambah Peserta -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="storeForm" action="{{ route('kelas.peserta.store') }}" method="POST">
        @csrf
        <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">

        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="createModalLabel">{{__('admin.kelas.peserta.create.header')}}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>

        <div class="modal-body">
          <div class="form-group mb-3">
            <label for="user_id" class="form-label">{{__('admin.kelas.peserta.create.choose_participant')}}</label>
            <select class="form-control select2" name="user_id" id="user_id" required style="width: 100%">
              <option value="">-- {{__('admin.kelas.peserta.create.choose_participant')}} --</option>
              @foreach($siswa as $s)
                <option value="{{ $s->id }}" {{ (int)old('user_id') === $s->id ? 'selected' : '' }}>
                  {{ $s->name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('admin.button.cancel')}}</button>
          <button type="button" id="btnCreate" class="btn btn-primary">{{__('admin.button.save')}}</button>
        </div>
      </form>
    </div>
  </div>
</div>
