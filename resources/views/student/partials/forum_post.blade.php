{{-- File: resources/views/student/partials/forum_post.blade.php --}}
@php
    $initials = strtoupper(substr($post->user->name, 0, 2));
@endphp

<div class="post-wrapper" id="post-{{ $post->id }}">
    {{-- AVATAR (Kiri) --}}
    <div class="post-avatar {{ $is_reply ? 'post-avatar-reply' : '' }}">
        {{ $initials }}
    </div>

    {{-- BODY POSTINGAN (Kanan) --}}
    <div class="post-body">
        <div class="card post-card team-{{ $post->team }}">
            {{-- Header Postingan --}}
            <div class="post-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $post->user->name }}</strong>
                        (<span class="fw-bold text-{{ $post->team == 'pro' ? 'success' : 'danger' }}">
                            {{ __('admin.siswa.forum_post.team') }} {{ $post->team }}
                        </span>)

                        @if($post->parent_post_id)
                            <small class="text-muted">
                                {{ __('admin.siswa.forum_post.reply') }}
                                <strong>{{ $post->parentPost->user->name ?? __('admin.siswa.forum_post.post') }}</strong>
                            </small>
                        @endif
                    </div>
                    <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                </div>
            </div>


            {{-- Konten Postingan --}}
            <div class="post-content rich-text-content">
                {!! $post->content !!}
            </div>

            {{-- Bukti (Sesuai file Anda) --}}
            @if($post->evidence->isNotEmpty())
                <div class="evidence-list p-3 border-top">
                    <strong>{{__('admin.siswa.forum_post.attach_evidence')}}:</strong>
                    <ul>
                        @php $evidenceData = []; @endphp
                        @foreach($post->evidence as $submission)
                            @php
                            $evidenceData[] = [
                                'url' => asset('storage/' . $submission->file_path),
                                'label' => $submission->original_filename,
                                'type' => $submission->slot->phyphox_experiment_type ?? 'line'
                            ];
                            @endphp
                            <li>
                                <i class="fa fa-file-csv text-success"></i>
                                {{ $submission->original_filename ?? __('admin.siswa.forum_post.evidence_file') }}
                                @if($submission->slot)
                                <small class="text-muted">(dari: {{ $submission->slot->label }})</small>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                    <button class="btn btn-sm btn-outline-info view-evidence-btn mt-2"
                            data-evidence-json="{{ json_encode($evidenceData) }}"
                            data-canvas-id="evidence-chart-canvas-{{ $post->id }}">
                        <i class="fa fa-bar-chart me-2"></i> {{__('admin.siswa.forum_post.show_chart')}}
                    </button>

                    <div class="mt-3" style="display:none;" id="evidence-chart-container-{{ $post->id }}">
                        <canvas id="evidence-chart-canvas-{{ $post->id }}"></canvas>
                    </div>
                </div>
            @endif

            {{-- [PERBAIKAN] Footer (Tombol Balas) --}}
            {{-- Hanya tampilkan jika $isAdminView false atau tidak didefinisikan --}}
            @if(empty($isAdminView))
                <div class="post-footer text-end">
                    <button class="btn btn-sm btn-outline-secondary reply-btn"
                            data-post-id="{{ $post->id }}">
                        <i class="fa fa-reply"></i> {{__('admin.siswa.forum_post.reply_button')}}
                    </button>
                </div>
            @endif
        </div>

        {{-- Kontainer Kosong untuk Form Balasan --}}
        <div id="reply-container-{{ $post->id }}"></div>

        {{-- Kontainer untuk balasan (Rekursif) --}}
        <div class="post-replies">
            @foreach($post->replies as $reply)
                {{-- [PERBAIKAN] Teruskan variabel $isAdminView --}}
                @include('student.partials.forum_post', [
                    'post' => $reply,
                    'is_reply' => true,
                    'mySubmissions' => $mySubmissions,
                    'isAdminView' => $isAdminView ?? false
                ])
            @endforeach
        </div>
    </div>
</div>
