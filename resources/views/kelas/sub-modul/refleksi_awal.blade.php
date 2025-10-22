<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header justify-content-between">
                <div>
                    <h2 class="card-title mb-10">Pengantar & Konteks {{$kelas->nama_kelas}}</h2>
                </div>                                    
            </div>

            <!-- Scrollable content area -->
            <div class="card-body p-0">
                <div class="card-title">Pertanyaan Refleksi Awal {{$kelas->modul->judul_id}}</div>
                <form action="{{ route('kelas.jawaban.simpan', [$kelas->id]) }}" method="POST" class="w-100">
                    @csrf
                    <input type="hidden" name="modul_id" value="{{ $kelas->modul->id }}">
                    <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">

                    <div class="mb-3">
                        @foreach($refleksi_awal as $r)
                            <div class="form-group mb-2">
                                <label for="jawaban_{{ $r->id }}" class="form-label">{{ $r->pertanyaan }}</label>
                                <textarea id="jawaban_{{ $r->id }}" name="jawaban[{{ $r->id }}]" class="form-control" rows="2" placeholder="Tulis jawaban Anda..." required></textarea>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            Simpan Jawaban
                        </button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
