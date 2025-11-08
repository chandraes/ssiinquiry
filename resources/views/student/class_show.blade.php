@extends('layouts.app')

@section('title')
    {{__('admin.siswa.class_show.title')}}: {{ $kelas->nama_kelas }}
@endsection

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="card-title">{{ $modul->judul }}</h2>
            <p class="text-muted h5 mb-2">{{__('admin.siswa.class_show.welcome')}}: <strong>{{ $kelas->nama_kelas }}</strong></p>
            <p class="card-text">{{ $modul->deskripsi }}</p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">{{ __('admin.siswa.class_show.header') }}</h5>
            </div>

            <div>
                @if($modul->rps_file)
                    <a href="{{ asset('storage/' . $modul->rps_file) }}"
                    target="_blank"
                    class="btn btn-primary" title='{{ __("admin.siswa.class_show.learning_plan_title") }}'>
                        <i class="fa fa-file me-2"></i>
                        {{ __('admin.siswa.class_show.learning_plan') }}
                    </a>
                @else
                    <span class="text-danger small">
                        <i class="fa fa-info-circle me-1"></i>
                        {{ __('admin.siswa.class_show.no_learning_plan_file') }}
                    </span>
                @endif
            </div>
        </div>

        <div class="card-body">
            <div class="list-group">
                @php $previousCompleted = true; @endphp
                @forelse($subModules as $subModule)
                    @php
                        $progress = $progressData->get($subModule->id);
                        $isCompleted = $progress && $progress->completed_at;
                        $isAvailable = $previousCompleted;
                        $isLocked = !$isAvailable;

                        $linkClass = 'list-group-item list-group-item-action p-3 mb-2 border';
                        $linkHref = '#';
                        $statusIcon = '';
                        $statusTitle = '';

                        if ($isCompleted) {
                            $linkClass .= ' list-group-item-success-light';
                            $linkHref = route('student.submodule.show', [$kelas->id, $subModule->id]);
                            $statusIcon = '✅';
                            $statusTitle = __('admin.siswa.class_show.finish');
                        } elseif ($isAvailable) {
                            $linkHref = route('student.submodule.show', [$kelas->id, $subModule->id]);
                            $statusIcon = '➡️';
                            $statusTitle = __('admin.siswa.class_show.available');
                        } else {
                            $linkClass .= ' list-group-item-light text-muted disabled';
                            $statusIcon = '🔒';
                            $statusTitle = __('admin.siswa.class_show.locked');
                        }
                    @endphp

                    <a href="{{ $linkHref }}"
                    class="{{ $linkClass }}"
                    aria-disabled="{{ $isLocked ? 'true' : 'false' }}"
                    title="{{ $statusTitle }}">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">
                                @if($subModule->type == 'learning') <i class="fa fa-book-open text-primary me-2"></i>
                                @elseif($subModule->type == 'reflection') <i class="fa fa-pencil-square text-info me-2"></i>
                                @elseif($subModule->type == 'practicum') <i class="fa fa-flask text-success me-2"></i>
                                @elseif($subModule->type == 'forum') <i class="fa fa-comments text-danger me-2"></i>
                                @endif

                                {{ $subModule->title }}
                            </h5>
                            <small class="ms-2">{{ $statusIcon }} {{ $statusTitle }}</small>
                        </div>
                        <p class="mb-1 {{ $isLocked ? '' : 'text-muted' }}">
                            {!! Str::limit($subModule->description, 150) !!}
                        </p>
                    </a>

                    @php $previousCompleted = $isCompleted; @endphp
                @empty
                    <div class="alert alert-light text-center">
                        {{ __('admin.siswa.class_show.no_curriculum') }}.
                    </div>
                @endforelse

                <div class="card shadow-sm mb-3">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ __('admin.siswa.class_show.summary_grade') }}</h5>
                        <p class="card-text text-muted">{{ __('admin.siswa.class_show.card_text') }}.</p>
                        <a href="{{ route('student.class.grades', $kelas->id) }}" class="btn btn-primary btn-lg">
                            <i class="fa fa-star me-2"></i> {{ __('admin.siswa.class_show.transcript') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <a href="{{ route('home') }}" class="btn btn-secondary btn-lg">
                <i class="fa fa-arrow-left me-2"></i> {{__('admin.button.back_to')}} {{__('admin.dashboard.title')}}
            </a>
        </div>
    </div>
</div>
@endsection
