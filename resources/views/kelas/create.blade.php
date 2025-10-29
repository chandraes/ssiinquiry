<div class="modal fade" id="createModalKelas" tabindex="-1" role="dialog" aria-labelledby="createModalKelasLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalKelasLabel">
                    {{ __('admin.kelas.create.header') }}
                    <strong id="createModalKelasJudul" class="text-primary"></strong>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <form id="storeFormKelas" action="{{ route('kelas.store') }}" method="POST">
                    @csrf

                    {{-- Hidden input modul_id, diisi oleh JS jika ada --}}
                    <input type="hidden" name="modul_id" id="modul_id_for_kelas" value="{{ old('modul_id') }}">

                    {{-- Jika modul_id kosong, tampilkan select option --}}
                    @if(empty(old('modul_id')))
                        <div class="form-group mb-3">
                            <label for="modul_id" class="form-label">{{ __('admin.kelas.create.module') }}</label>
                            <select class="form-control" id="modul_id" name="modul_id" style="width: 100%;">
                                <option value="">{{ __('admin.kelas.create.choose_module') }}</option>
                                @foreach($modul as $m)
                                    <option value="{{ $m->id }}">{{ $m->judul}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- Nama Kelas (ID) --}}
                    <div class="form-group mb-3">
                        <label for="nama_kelas_id" class="form-label">{{ __('admin.kelas.create.class_name') }} (ID)</label>
                        <input type="text" class="form-control" id="nama_kelas_id" name="nama_kelas[id]" required>
                    </div>

                    {{-- Nama Kelas (EN) --}}
                    <div class="form-group mb-3">
                        <label for="nama_kelas_en" class="form-label">{{ __('admin.kelas.create.class_name') }} (EN)</label>
                        <input type="text" class="form-control" id="nama_kelas_en" name="nama_kelas[en]" required>
                    </div>

                    {{-- Guru Pengajar --}}
                    <div class="form-group mb-3">
                        <label for="guru_id" class="form-label">{{ __('admin.kelas.create.teacher') }} (Owner)</label>
                        <select class="form-control" id="guru_id" name="guru_id" style="width: 100%;">
                            {{-- Select2 akan mengisi ini --}}
                        </select>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.button.close') }}</button>
                <button type="button" class="btn btn-primary" id="btnCreateKelas">{{ __('admin.button.save') }}</button>
            </div>
        </div>
    </div>
</div>
