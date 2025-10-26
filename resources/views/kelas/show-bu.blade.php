@extends('layouts.app')

@section('title')
    {{__('admin.class_show.title')}} {{ $kelas->nama_kelas }}
@endsection

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="card-title">{{ $kelas->nama_kelas }}</h2>
            <p class="text-muted mb-1">{{__('admin.class_show.module')}} :<strong>{{ $kelas->modul->judul }}</strong></p>
            <p class="text-muted mb-1">{{__('admin.class_show.teacher')}} : <strong>{{ $kelas->guru?->name }}</strong></p>
            <p class="text-muted mb-0">{{__('admin.class_show.num_of_students')}} : <strong>{{ $kelas->peserta?->count() }} {{__('admin.class_show.students')}} </strong></p>
        </div>
    </div>

    <div class="row">

        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center d-flex flex-column justify-content-between">
                    <div>
                        <i class="fa fa-users fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">{{__('admin.class_show.students_management')}}</h5>
                        <p class="card-text text-muted">{{__('admin.class_show.add_delete_students')}}.</p>
                    </div>
                    <a href="{{ route('kelas.peserta', $kelas->id) }}" class="btn btn-primary mt-3">
                        {{__('admin.class_show.set_students')}}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center d-flex flex-column justify-content-between">
                    <div>
                        <i class="fa fa-comments fa-3x text-success mb-3"></i>
                        <h5 class="card-title">{{__('admin.class_show.forum_management')}}</h5>
                        <p class="card-text text-muted">{{__('admin.class_show.set_pro_contra')}}.</p>
                    </div>
                    {{-- Ini adalah link ke halaman "hub forum" yang kita buat sebelumnya --}}
                    <a href="{{ route('kelas.forums', $kelas->id) }}" class="btn btn-success mt-3">
                        {{__('admin.class_show.set_forum')}}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card h-100 shadow-sm border-dashed">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <i class="fa fa-bar-chart fa-3x text-muted mb-3"></i>
                    <h5 class="card-title text-muted">{{__('admin.class_show.report_grades')}}</h5>
                    <p class="card-text text-muted">({{__('admin.class_show.coming_soon')}})</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
