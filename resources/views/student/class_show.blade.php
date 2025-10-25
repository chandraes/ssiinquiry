@extends('layouts.app')

@section('title')
    Kelas: {{ $kelas->nama_kelas }}
@endsection

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm mb-3">
                <i class="fa fa-arrow-left me-2"></i> Kembali ke Dashboard
            </a>

            <h2 class="card-title">{{ $modul->judul }}</h2>
            <p class="text-muted h5 mb-2">Selamat datang di kelas: <strong>{{ $kelas->nama_kelas }}</strong></p>
            <p class="card-text">{{ $modul->deskripsi }}</p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Kurikulum Pembelajaran</h5>
        </div>
        <div class="card-body">
            <div class="list-group">
                @php $previousCompleted = true; // Anggap sub-modul pertama selalu bisa diakses @endphp
                @forelse($subModules as $subModule)
                    @php
                        // Cek status progress untuk sub-modul ini
                        $progress = $progressData->get($subModule->id); // Ambil progress dari data yg dikirim controller
                        $isCompleted = $progress && $progress->completed_at; // Apakah sudah selesai?
                        $isAvailable = $previousCompleted; // Apakah bisa diakses (sebelumnya selesai)?
                        $isLocked = !$isAvailable; // Apakah terkunci?

                        // Siapkan kelas CSS dan link
                        $linkClass = 'list-group-item list-group-item-action p-3 mb-2 border';
                        $linkHref = '#'; // Default tidak ada link
                        $statusIcon = '';
                        $statusTitle = '';

                        if ($isCompleted) {
                            $linkClass .= ' list-group-item-success-light'; // Warna hijau muda
                            $linkHref = route('student.submodule.show', [$kelas->id, $subModule->id]); // Buat link ini nanti
                            $statusIcon = '✅';
                            $statusTitle = 'Selesai';
                        } elseif ($isAvailable) {
                            $linkHref = route('student.submodule.show', [$kelas->id, $subModule->id]); // Buat link ini nanti
                            $statusIcon = '➡️';
                            $statusTitle = 'Tersedia';
                        } else { // Terkunci
                            $linkClass .= ' list-group-item-light text-muted disabled'; // Warna abu & non-aktif
                            $statusIcon = '🔒';
                            $statusTitle = 'Terkunci';
                        }
                    @endphp

                    {{-- Tampilkan item list group --}}
                    <a href="{{ $linkHref }}"
                       class="{{ $linkClass }}"
                       aria-disabled="{{ $isLocked ? 'true' : 'false' }}"
                       title="{{ $statusTitle }}">

                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">
                                {{-- Ikon Tipe --}}
                                @if($subModule->type == 'learning') <i class="fa fa-book-open text-primary me-2"></i>
                                @elseif($subModule->type == 'reflection') <i class="fa fa-pencil-square text-info me-2"></i>
                                @elseif($subModule->type == 'practicum') <i class="fa fa-flask text-success me-2"></i>
                                @elseif($subModule->type == 'forum') <i class="fa fa-comments text-danger me-2"></i>
                                @endif

                                {{ $subModule->title }}
                            </h5>
                            {{-- Tampilkan Status --}}
                            <small class="ms-2">{{ $statusIcon }} {{ $statusTitle }}</small>
                        </div>
                        <p class="mb-1 {{ $isLocked ? '' : 'text-muted' }}">
                            {{ Str::limit($subModule->description, 150) }}
                        </p>
                    </a>

                    @php
                        // Update status untuk iterasi berikutnya
                        // Modul selanjutnya hanya bisa diakses jika modul INI selesai
                        $previousCompleted = $isCompleted;
                    @endphp

                @empty
                    <div class="alert alert-light text-center">
                        Kurikulum untuk modul ini belum ditambahkan oleh guru.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
