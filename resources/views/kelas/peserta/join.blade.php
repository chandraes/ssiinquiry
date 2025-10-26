<div class="modal fade" id="joinModal" tabindex="-1" aria-labelledby="joinKelasModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      {{-- Ganti action sesuai route yang Anda buat untuk proses join, misalnya route('kelas.join') --}}
      <form id="storeForm" action="{{ route('siswa.kelas.join', ['kelas_id' => $kelas->id]) }}" method="POST">
        @csrf
        
        {{-- Hidden input untuk mengirim ID Kelas. Pastikan $kelas didefinisikan di view yang memanggil modal. --}}
        <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">

        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="joinKelasModalLabel">{{__('admin.class_participants.join.title')}}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        
        <div class="modal-body">
          <p>{{__('admin.class_participants.join.text')}} <strong>{{ $kelas->nama_kelas ?? 'Nama Kelas' }}</strong> {{__('admin.class_participants.join.an')}} {{ $userLogin->name ?? '{{__('admin.class_participants.join.user')}}' }}.</p>
          
          <div class="form-group mb-3">
            <label for="kode_join" class="form-label">{{__('admin.class_participants.join.instruction')}}</label>
            {{-- Input ini adalah KUNCI untuk proses join --}}
            <input type="text" name="kode_join" id="kode_join" class="form-control" placeholder='{{__('admin.class_participants.join.placeholder')}}' required>
            
            {{-- Pesan error, berguna jika form gagal validasi --}}
            @error('kode_join')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('admin.class_participants.join.cancel')}}</button>
          {{-- Ganti id dan type menjadi submit --}}
          <button type="submit" id="btnJoin" class="btn btn-primary">{{__('admin.class_participants.join.join_class')}}</button>
        </div>
      </form>
    </div>
  </div>
</div>