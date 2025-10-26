@extends('layouts.app')

@section('title')
    {{ __('admin.forum_settings.title') }} {{ $subModule->title }}
@endsection

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="card-title">{{ __('admin.forum_settings.title') }}  {{ $subModule->title }}</h2>
            <p class="text-muted">
                {{ __('admin.forum_settings.setting_team_for_class') }} <strong>{{ $kelas->nama_kelas }}</strong>
            </p>
            <a href="{{ route('kelas.forums', $kelas->id) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fa fa-arrow-left me-2"></i>{{ __('admin.forum_settings.back_text') }}
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
         <div class="card-header">
             <h5 class="mb-0">{{ __('admin.forum_settings.team_management') }}</h5>
         </div>

         {{-- Container ini menyimpan ID yang dibutuhkan oleh JavaScript --}}
         <div class="card-body" id="team-management-container"
              data-submodule-id="{{ $subModule->id }}"
              data-kelas-id="{{ $kelas->id }}">

             <div class="row">

                 <div class="col-md-4">
                     <h6 class="border-bottom pb-2" id="unassigned-count">
                         {{ __('admin.forum_settings.unassigned_students') }} ({{ $unassignedStudents->count() }})
                     </h6>
                     <ul class="list-group" id="unassigned-list" style="min-height: 200px;">
                         @forelse($unassignedStudents as $student)
                         <li class="list-group-item d-flex justify-content-between align-items-center" data-user-id="{{ $student->id }}">
                             {{ $student->name }}
                             <div>
                                 <button class="btn btn-success btn-sm assign-pro" title="{{ __('admin.forum_settings.assign_pro') }}">+</button>
                                 <button class="btn btn-danger btn-sm assign-con" title="{{ __('admin.forum_settings.assign_con') }}">-</button>
                             </div>
                         </li>
                         @empty
                         {{-- Item ini akan disembunyikan oleh JS jika ada anggota --}}
                         <li class="list-group-item text-muted no-members">{{ __('admin.forum_settings.no_students_in_class') }}</li>
                         @endforelse
                     </ul>
                 </div>

                 <div class="col-md-4">
                     <h6 class="border-bottom pb-2 text-success" id="pro-count">
                         {{ __('admin.forum_settings.team_pro') }} ({{ $proTeam->count() }})
                     </h6>
                     <ul class="list-group" id="pro-team-list" style="min-height: 200px;">
                         @forelse($proTeam as $member)
                             <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-success" data-user-id="{{ $member->id }}">
                                 {{ $member->name }}
                                 <button class="btn btn-secondary btn-sm remove-member" title="{{ __('admin.forum_settings.remove_from_team') }}">x</button>
                             </li>
                         @empty
                             {{-- Item ini akan disembunyikan oleh JS jika ada anggota --}}
                             <li class="list-group-item text-muted no-members">{{ __('admin.forum_settings.no_members') }}</li>
                         @endforelse
                     </ul>
                 </div>

                 <div class="col-md-4">
                     <h6 class="border-bottom pb-2 text-danger" id="con-count">
                         {{ __('admin.forum_settings.team_con') }} ({{ $conTeam->count() }})
                     </h6>
                     <ul class="list-group" id="con-team-list" style="min-height: 200px;">
                         @forelse($conTeam as $member)
                             <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-danger" data-user-id="{{ $member->id }}">
                                 {{ $member->name }}
                                 <button class="btn btn-secondary btn-sm remove-member" title="{{ __('admin.forum_settings.remove_from_team') }}">x</button>
                             </li>
                         @empty
                             {{-- Item ini akan disembunyikan oleh JS jika ada anggota --}}
                             <li class="list-group-item text-muted no-members">{{ __('admin.forum_settings.no_members') }}</li>
                         @endforelse
                     </ul>
                 </div>
             </div>
         </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {

    // 1. Setup AJAX untuk mengirim CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // 2. Ambil data ID dari container
    const container = $('#team-management-container');
    const subModuleId = container.data('submodule-id');
    const kelasId = container.data('kelas-id');

    // 3. Event Listener untuk tombol 'Assign' (pakai event delegation)
    $('#unassigned-list').on('click', '.assign-pro', function() {
        const $li = $(this).closest('li');
        const userId = $li.data('user-id');
        assignTeam(userId, 'pro', $li);
    });

    $('#unassigned-list').on('click', '.assign-con', function() {
        const $li = $(this).closest('li');
        const userId = $li.data('user-id');
        assignTeam(userId, 'con', $li);
    });

    // 4. Event Listener untuk tombol 'Remove'
    $('#pro-team-list, #con-team-list').on('click', '.remove-member', function() {
        const $li = $(this).closest('li');
        const userId = $li.data('user-id');
        removeTeam(userId, $li);
    });

    // 5. Fungsi AJAX untuk MENAMBAHKAN ke tim
    function assignTeam(userId, team, $liElement) {
        // Tampilkan loading (opsional)
        $liElement.css('opacity', 0.5);

        $.ajax({
            url: '{{ route("forum.teams.assign") }}',
            type: 'POST',
            data: {
                sub_module_id: subModuleId,
                kelas_id: kelasId,
                user_id: userId,
                team: team
            },
            success: function(response) {
                // 1. Hapus tombol '+' dan '-'
                $liElement.find('div').remove();

                // 2. Tambahkan tombol 'x'
                $liElement.append(
                    '<button class="btn btn-secondary btn-sm remove-member" title="{{ __("admin.forum_settings.remove_from_team") }}">x</button>'
                );

                // 3. Pindahkan <li> ke list yang benar
                if (team === 'pro') {
                    $liElement.removeClass('list-group-item-danger').addClass('list-group-item-success');
                    $('#pro-team-list').append($liElement.css('opacity', 1));
                } else {
                    $liElement.removeClass('list-group-item-success').addClass('list-group-item-danger');
                    $('#con-team-list').append($liElement.css('opacity', 1));
                }
                updateCounters();
            },
            error: function() {
                Swal.fire('Error', 'Gagal memperbarui tim.', 'error');
                $liElement.css('opacity', 1);
            }
        });
    }

    // 6. Fungsi AJAX untuk MENGHAPUS dari tim
    function removeTeam(userId, $liElement) {
        $liElement.css('opacity', 0.5);

        $.ajax({
            url: '{{ route("forum.teams.remove") }}',
            type: 'POST',
            data: {
                sub_module_id: subModuleId,
                kelas_id: kelasId,
                user_id: userId
            },
            success: function(response) {
                // 1. Hapus tombol 'x'
                $liElement.find('button').remove();

                // 2. Tambahkan kembali tombol '+' dan '-'
                $liElement.append(
                    '<div>' +
                    '<button class="btn btn-success btn-sm assign-pro" title="{{ __("admin.forum_settings.assign_pro") }}">+</button> ' +
                    '<button class="btn btn-danger btn-sm assign-con" title="{{ __("admin.forum_settings.assign_con") }}">-</button>' +
                    '</div>'
                );

                // 3. Pindahkan <li> kembali ke 'unassigned'
                $liElement.removeClass('list-group-item-success list-group-item-danger');
                $('#unassigned-list').append($liElement.css('opacity', 1));
                updateCounters();
            },
            error: function() {
                Swal.fire('Error', 'Gagal menghapus siswa dari tim.', 'error');
                $liElement.css('opacity', 1);
            }
        });
    }

    // 7. Fungsi helper untuk update hitungan
    function updateCounters() {
        // Update teks hitungan
        $('#unassigned-count').text(
            '{{ __("admin.forum_settings.unassigned_students") }} (' + $('#unassigned-list li[data-user-id]').length + ')'
        );
        $('#pro-count').text(
            '{{ __("admin.forum_settings.team_pro") }} (' + $('#pro-team-list li[data-user-id]').length + ')'
        );
        $('#con-count').text(
            '{{ __("admin.forum_settings.team_con") }} (' + $('#con-team-list li[data-user-id]').length + ')'
        );

        // Tampilkan/sembunyikan pesan 'Belum ada anggota/siswa'
        $('#unassigned-list .no-members').toggle($('#unassigned-list li[data-user-id]').length === 0);
        $('#pro-team-list .no-members').toggle($('#pro-team-list li[data-user-id]').length === 0);
        $('#con-team-list .no-members').toggle($('#con-team-list li[data-user-id]').length === 0);
    }

    // Panggil sekali saat load untuk menyembunyikan/menampilkan pesan 'no-members'
    updateCounters();

});
</script>
@endpush
