<div class="modal fade" id="joinModal" tabindex="-1" aria-labelledby="joinKelasModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      {{-- Ganti action sesuai route yang Anda buat untuk proses join, misalnya route('kelas.join') --}}
      <form id="storeForm" action="{{ route('siswa.kelas.join', ['kelas_id' => $kelas->id]) }}" method="POST">
        @csrf
        
        {{-- Hidden input untuk mengirim ID Kelas. Pastikan $kelas didefinisikan di view yang memanggil modal. --}}
        <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">

        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="joinKelasModalLabel">{{__('admin.kelas.peserta.join.header')}}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        
        <div class="modal-body">
          <p>{{__('admin.kelas.peserta.join.class')}}: <strong>{{ $kelas->nama_kelas ?? 'Nama Kelas' }}</strong> atas nama **{{ $userLogin->name ?? 'Pengguna' }}**.</p>
          
          <div class="form-group mb-3">
            <label for="kode_join" class="form-label">{{__('admin.kelas.peserta.join.instruction')}}</label>
            {{-- Input ini adalah KUNCI untuk proses join --}}
            <input type="text" name="kode_join" id="kode_join" class="form-control" placeholder="{{__('admin.kelas.peserta.join.placeholder')}}" required>
            
            {{-- Pesan error, berguna jika form gagal validasi --}}
            @error('kode_join')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('admin.button.cancel')}}</button>
          {{-- Ganti id dan type menjadi submit --}}
          <button type="submit" id="btnJoin" class="btn btn-primary">{{__('admin.kelas.peserta.join.join_button')}}</button>
        </div>
      </form>
    </div>
  </div>
</div>