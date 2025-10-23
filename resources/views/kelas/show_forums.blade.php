@extends('layouts.app')

@section('title')
    Manajemen Forum: {{ $kelas->nama_kelas }}
@endsection

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="card-title">Manajemen Tim Forum</h2>
            <p class="text-muted">Kelas: <strong>{{ $kelas->nama_kelas }}</strong></p>
            <p>
                Pilih salah satu sub-modul forum di bawah ini untuk memulai
                pengaturan tim Pro dan Kontra untuk kelas ini.
            </p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Forum Tersedia (dari Modul: {{ $kelas->modul->judul }})</h5>
        </div>
        <div class="card-body">
            <div class="list-group">
                @forelse($forumSubModules as $subModule)
                    <a href="{{ route('kelas.forum.teams', [$kelas->id, $subModule->id]) }}"
                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">{{ $subModule->title }}</h6>
                            <small class="text-muted">{{ $subModule->getTranslation('debate_topic', app()->getLocale()) }}</small>
                        </div>
                        <span class="btn btn-primary btn-sm">
                            Atur Tim <i class="fa fa-arrow-right ms-2"></i>
                        </span>
                    </a>
                @empty
                    <div class="alert alert-info text-center">
                        Tidak ada sub-modul forum yang ditemukan untuk modul ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
