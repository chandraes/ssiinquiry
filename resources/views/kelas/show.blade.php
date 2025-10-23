@extends('layouts.app')

@section('title')
    Detail Kelas: {{ $kelas->nama_kelas }}
@endsection

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="card-title">{{ $kelas->nama_kelas }}</h2>
            <p class="text-muted mb-1">Modul: <strong>{{ $kelas->modul->judul }}</strong></p>
            <p class="text-muted mb-1">Guru Pengajar: <strong>{{ $kelas->guru?->name }}</strong></p>
            <p class="text-muted mb-0">Jumlah Peserta: <strong>{{ $kelas->peserta?->count() }} Siswa</strong></p>
        </div>
    </div>

    <div class="row">

        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center d-flex flex-column justify-content-between">
                    <div>
                        <i class="fa fa-users fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Manajemen Peserta</h5>
                        <p class="card-text text-muted">Tambah atau hapus siswa dari kelas ini.</p>
                    </div>
                    <a href="{{ route('kelas.peserta', $kelas->id) }}" class="btn btn-primary mt-3">
                        Kelola Peserta
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center d-flex flex-column justify-content-between">
                    <div>
                        <i class="fa fa-comments fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Manajemen Forum</h5>
                        <p class="card-text text-muted">Atur tim Pro dan Kontra untuk sub-modul debat di kelas ini.</p>
                    </div>
                    {{-- Ini adalah link ke halaman "hub forum" yang kita buat sebelumnya --}}
                    <a href="{{ route('kelas.forums', $kelas->id) }}" class="btn btn-success mt-3">
                        Atur Forum
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card h-100 shadow-sm border-dashed">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <i class="fa fa-bar-chart fa-3x text-muted mb-3"></i>
                    <h5 class="card-title text-muted">Laporan & Nilai</h5>
                    <p class="card-text text-muted">(Segera Hadir)</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
