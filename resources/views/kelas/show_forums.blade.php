@extends('layouts.app')

@section('title')
    {{__('admin.forum.title')}}: {{ $kelas->nama_kelas }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="card-title">{{__('admin.forum.sub_title')}}</h2>
            <p class="text-muted">{{__('admin.forum.class')}}: <strong>{{ $kelas->nama_kelas }}</strong></p>
            <p>
                {{__('admin.forum.instruction')}}
            </p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">{{__('admin.forum.forum_available')}} ({{ $kelas->modul->judul }})</h5>
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
                            {{__('admin.forum.set_team')}} <i class="fa fa-arrow-right ms-2"></i>
                        </span>
                    </a>
                @empty
                    <div class="alert alert-info text-center">
                        {{__('admin.forum.no_submodul')}}
                    </div>
                @endforelse
            </div>
        </div>
        <div class="card-footer">
            <div class="col-md-12">
                <a href="{{ route('kelas.show', $kelas->id) }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left me-1"></i> {{ __('admin.button.back_to') }} {{ __('admin.kelas.show.header') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
