
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header justify-content-between">
                <div>
                    <h2 class="card-title mb-10">Pengantar & Konteks {{$kelas->nama_kelas}}</h2>
                </div>                                    
            </div>

            <!-- Scrollable content area -->
            <div class="card-body">
                {{-- <div class="scrollable mb-10" style="max-height:480px; overflow-y:auto; padding:1rem;"> --}}
                    <div class="card-title text-center">
                        Pengantar
                    </div>
                    <p><strong>{{$pengantar->judul}}</strong></p>
                    <p class="text-justified p-5">
                        {{$pengantar->pengantar}}
                    </p>

                    <hr>
                    <div class="card-title text-center mt-6">
                        Tujuan Pembelajaran {{$kelas->modul->judul_id}}
                    </div>
                    <ol class="mb-5 text-justified">
                        @foreach($tujuan as $t)
                            <li class="mb-2"><strong>{{$t->judul}} :</strong> {{$t->tujuan}}</li>
                        @endforeach
                    </ol>
                </div>
                <div class="card-body p-0">
                    <div class="card-title text-center mt-6">
                        Materi Pembelajaran Awal {{$kelas->modul->judul_id}}
                    </div>
                    <div class="mx-5">
                        <p class="mb-0"><strong>Video Dokumenter / Liputan</strong></p>
                        <ul class="mb-5 text-justified">
                            @foreach($materi_awal as $ma)
                                <li>
                                    <a href="{{ $ma->video_liputan }}" target="_blank" rel="noopener">
                                        {{ $ma->video_liputan }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <p class="mb-0"><strong>Artikel Berita / Opini</strong></p>
                        <ul class="mb-5 text-justified">
                            @foreach($materi_awal as $ma)
                                <li>
                                    <a href="{{ $ma->artikel_opini }}" target="_blank" rel="noopener">
                                        {{ $ma->artikel_opini }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <p class="mb-0"><strong>Infografis</strong></p>
                        <ul class="mb-5 text-justified">
                            @foreach($materi_awal as $ma)
                                <li>
                                    <a href="{{ $ma->infografis }}" target="_blank" rel="noopener">
                                        {{ $ma->infografis }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        
                        <p class="mb-0"><strong>Regulasi</strong></p>
                        <ul class="mb-5 text-justified">
                            @foreach($materi_awal as $ma)
                                <li>
                                    <a href="{{ $ma->regulasi }}" target="_blank" rel="noopener">
                                        {{ $ma->regulasi }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="card-title text-center">Pertanyaan Refleksi Awal {{$kelas->modul->judul_id}}</div>
                    <form action="{{ route('kelas.jawaban.simpan', [$kelas->id]) }}" method="POST" class="w-100">
                        @csrf
                        <input type="hidden" name="modul_id" value="{{ $kelas->modul->id }}">
                        <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">

                        <ol class="mb-3">
                            @foreach($refleksi_awal as $r)
                                <li class="mb-3">
                                    <div class="form-group">
                                        <label for="jawaban_{{ $r->id }}" class="form-label">{{ $r->pertanyaan }}</label>
                                        <textarea id="jawaban_{{ $r->id }}" name="jawaban[{{ $r->id }}]" class="form-control" rows="2" placeholder="Tulis jawaban Anda..." required></textarea>
                                    </div>
                                </li>
                            @endforeach
                        </ol>

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

           