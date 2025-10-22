<div class="modal fade" id="joinModal" tabindex="-1" aria-labelledby="joinKelasModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      {{-- Ganti action sesuai route yang Anda buat untuk proses join, misalnya route('kelas.join') --}}
      <form id="storeForm" action="{{ route('siswa.kelas.join', ['kelas_id' => $kelas->id]) }}" method="POST">
        @csrf
        
        {{-- Hidden input untuk mengirim ID Kelas. Pastikan $kelas didefinisikan di view yang memanggil modal. --}}
        <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">

        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="joinKelasModalLabel">Gabung ke Kelas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        
        <div class="modal-body">
          <p>Anda akan bergabung ke Kelas: <strong>{{ $kelas->nama_kelas ?? 'Nama Kelas' }}</strong> atas nama **{{ $userLogin->name ?? 'Pengguna' }}**.</p>
          
          <div class="form-group mb-3">
            <label for="kode_join" class="form-label">Masukkan Kode Kelas (Kode Join)</label>
            {{-- Input ini adalah KUNCI untuk proses join --}}
            <input type="text" name="kode_join" id="kode_join" class="form-control" placeholder="Contoh: KLS1234" required>
            
            {{-- Pesan error, berguna jika form gagal validasi --}}
            @error('kode_join')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          {{-- Ganti id dan type menjadi submit --}}
          <button type="submit" id="btnJoin" class="btn btn-primary">Gabung Sekarang</button>
        </div>
      </form>
    </div>
  </div>
</div>