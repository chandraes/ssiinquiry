@extends('layouts.app')

@section('title')
    {{-- [BARU] --}}
    {{ __('admin.modul_list.page_title') }}
@endsection

@section('content')
@include('swal')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        {{-- [BARU] --}}
        <h2 class="card-title">{{ __('admin.modul_list.card_title') }}</h2>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

        <div class="col">
            <div class="card h-100 shadow-sm border-dashed"
                 style="cursor: pointer; border-style: dashed; border-width: 2px;"
                 data-bs-toggle="modal" data-bs-target="#createModal">
                <div class="card-body d-flex justify-content-center align-items-center">
                    <div class="text-center text-primary">
                        <i class="fa fa-plus fa-3x mb-3"></i>
                        {{-- [BARU] --}}
                        <h5 class="card-title mb-0">{{ __('admin.modul_list.add_new') }}</h5>
                    </div>
                </div>
            </div>
        </div>

        @forelse($moduls as $modul)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    @php
                        // Logika untuk menampilkan gambar atau placeholder
                        $imageUrl = $modul->image
                                    ? asset('storage/' . $modul->image)
                                    : 'https://via.placeholder.com/400x250?text=' . urlencode($modul->getTranslation('judul', 'en'));

                        dd($imageUrl);
                    @endphp

                    {{-- GAMBAR MODUL --}}
                    <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $modul->judul }}" style="height: 200px; object-fit: cover;">

                    <div class="card-body">
                        {{-- JUDUL (Spatie otomatis) --}}
                        <h5 class="card-title">{{ $modul->judul }}</h5>

                        {{-- DESKRIPSI (Spatie otomatis, dibatasi 100 karakter) --}}
                        <p class="card-text text-muted">
                            {{ Str::limit($modul->deskripsi, 100) }}
                        </p>
                    </div>

                    <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
                        {{-- TOMBOL DETAIL --}}
                        <a href="{{ route('modul.show', $modul->id) }}" class="btn btn-primary btn-sm">
                            {{ __('admin.modul_list.view_details') }}
                        </a>

                        {{-- Tombol Aksi (Edit/Delete) --}}
                        <div>
                            <button class="btn btn-warning btn-sm" title="{{ __('admin.modul_list.edit') }}">
                                <i class="fa fa-pencil"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" title="{{ __('admin.modul_list.delete') }}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    {{ __('admin.modul_list.no_modules') }}
                </div>
            </div>
        @endforelse

    </div>
</div>

{{-- JANGAN LUPA: Sertakan modal create Anda --}}
{{-- Pastikan $phyphox sudah dikirim dari controller --}}
@include('modul.create')

{{-- Jika Anda punya modal edit/delete, sertakan juga di sini --}}
{{-- @include('modul.edit') --}}

@endsection
