<div class="tab-pane fade" id="gradebook-pane" role="tabpanel" aria-labelledby="gradebook-tab" tabindex="0">
            <div class="card shadow-sm border-top-0 rounded-0 rounded-bottom">
                 {{-- Add padding for better spacing around table --}}
                <div class="card-body p-0 p-md-3">
                    <div class="table-responsive">
                        @php $totalMaxPoints = $subModules->sum('max_points'); @endphp
                        <table class="table table-bordered table-hover gradebook-table mb-0"> {{-- Removed mb-0 --}}
                            <thead class="table-light">
                                <tr>
                                    <th>{{__('admin.kelas.show.participant_table_name')}}</th>
                                    @foreach($subModules as $subModule)
                                        <th>
                                            {{ $subModule->title }}
                                            <span class="d-block small text-muted">
                                                {{ $subModule->max_points }} {{__('admin.kelas.show.participant_table_point')}}
                                            </span>
                                        </th>
                                    @endforeach
                                    <th>
                                        {{__('admin.kelas.show.participant_table_score')}}
                                        <span class="d-block small text-muted">
                                            {{ $totalMaxPoints }} {{__('admin.kelas.show.participant_table_point')}}
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    @php $studentTotalScore = 0; @endphp
                                    <tr>
                                        <td>{{ $student->name }}</td>
                                        @foreach($subModules as $subModule)
                                            @php
                                                $key = $student->id . '_' . $subModule->id;
                                                $progress = $allProgress->get($key);
                                                $cellClass = ''; $cellText = "{{__('admin.kelas.show.participant_table_grade')}}";
                                                if ($progress) {
                                                    if ($progress->score !== null) {
                                                        $cellClass = 'graded';
                                                        $cellText = $progress->score . ' / ' . $subModule->max_points;
                                                        $studentTotalScore += $progress->score;
                                                    } elseif ($progress->completed_at) {
                                                        $cellClass = 'completed';
                                                        $cellText = '<i class="fa fa-check-circle text-success me-1"></i>'. __('admin.kelas.show.participant_table_finish');
                                                    } else { $cellClass = 'draft'; $cellText = 'Draf'; }
                                                } else { $cellClass = 'pending'; $cellText = '-'; }
                                            @endphp
                                            <td>
                                                <a href="#"
                                                   class="grade-cell {{ $cellClass }}"
                                                   id="cell-{{ $student->id }}-{{ $subModule->id }}"
                                                   data-bs-toggle="modal" data-bs-target="#gradingModal"
                                                   data-student-id="{{ $student->id }}" data-student-name="{{ $student->name }}"
                                                   data-submodule-id="{{ $subModule->id }}" data-submodule-title="{{ $subModule->title }}"
                                                   data-submodule-type="{{ $subModule->type }}" data-max-points="{{ $subModule->max_points }}"
                                                   data-current-score="{{ $progress->score ?? '' }}" data-current-feedback="{{ $progress->feedback ?? '' }}">
                                                    {!! $cellText !!}
                                                </a>
                                            </td>
                                        @endforeach
                                        <td>
                                            <strong class="fs-5" id="total-score-{{ $student->id }}">
                                                {{ $studentTotalScore }} / {{ $totalMaxPoints }}
                                            </strong>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $subModules->count() + 2 }}" class="text-center p-4 text-muted">
                                            {{__('admin.kelas.show.no_participants')}}.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
