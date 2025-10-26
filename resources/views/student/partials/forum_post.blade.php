{{-- File: resources/views/student/partials/forum_post.blade.php --}}
<div class="card post-card team-{{ $post->team }} {{ $is_reply ? 'post-reply' : '' }}" id="post-{{ $post->id }}">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <div>
                <strong>{{ $post->user->name }}</strong>
                (<span class="fw-bold text-{{ $post->team == 'pro' ? 'success' : 'danger' }}">Tim {{ $post->team }}</span>)

                {{-- Tampilkan jika ini adalah balasan --}}
                @if($post->parent_post_id)
                    <small class="text-muted">membalas <strong>{{ $post->parentPost->user->name ?? 'post' }}</strong></small>
                @endif
            </div>
            <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
        </div>

        <hr>

        {{-- Konten Postingan --}}
        <div class="rich-text-content">
            {!! $post->content !!}
        </div>

        {{-- Bukti (Evidence) --}}
        @if($post->evidence->isNotEmpty())
            <div class="mt-3 evidence-list">
                <strong>Bukti Terlampir:</strong>
                <ul>
                    @foreach($post->evidence as $ev)
                        <li>
                            <i class="fa fa-file-csv text-success"></i>
                            {{ $ev->submission->original_filename ?? 'File Bukti' }}
                            {{-- TODO: Tambahkan link untuk melihat/download file CSV --}}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Tombol Balas (Hanya untuk postingan utama, bukan balasan) --}}
        @if(!$is_reply)
        <div class="text-end mt-2">
            <button class="btn btn-sm btn-outline-secondary reply-btn"
                    data-post-id="{{ $post->id }}"
                    data-post-user="{{ $post->user->name }}">
                <i class="fa fa-reply"></i> Balas
            </button>
        </div>
        @endif

    </div>
</div>

{{-- Tampilkan balasan (Recursive) --}}
@if(!$is_reply && $post->replies->isNotEmpty())
    @foreach($post->replies as $reply)
        @include('student.partials.forum_post', ['post' => $reply, 'is_reply' => true])
    @endforeach
@endif
