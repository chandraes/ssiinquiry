@extends('layouts.app')

@section('title')
{{__('admin.profile.title')}}
@endsection

@section('content')
@include('swal')
<section class="main-content mt-0">
    <div class="row" id="user-profile">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="wideget-user">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xl-4 d-flex justify-content-center align-items-center mb-4 mb-xl-0">
                                <div class="wideget-user-img">
                                    @if($user->profile && $user->profile->foto)
                                        <img class="rounded-circle shadow-sm" style="height:250px; width:250px; object-fit: cover;"
                                             src="{{ asset('storage/' . $user->profile?->foto) }}" alt="{{__('admin.profile.alt_image')}}">
                                     @else
                                        <img class="rounded-circle shadow-sm" style="height:250px; width:250px; object-fit: cover;"
                                             src="{{ asset('assets/images/users/default.jpg') }}" alt="{{__('admin.profile.alt_image')}}">
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xl-8">
                                <div class="user-wrap mt-xl-5">
                                    <h3>{{ $user->name ?? 'Guest' }}</h3>
                                    <h5 class="text-muted mb-3">{{ $user->roles->first()->name ?? '-' }}</h5>
                                </div>
                                <div class="mt-4">
                                    <a href="{{route('profile.edit')}}" class="btn btn-lg btn-primary">
                                        <i class="fe fe-edit me-1"></i> {{__('admin.profile.edit_profile')}}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="media-heading mb-3">
                        <h5><strong>{{__('admin.profile.information')}}</strong></h5>
                    </div>

                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="fw-bold text-muted">{{__('admin.profile.name')}}</span>
                            <span class="text-end">{{ $user->name }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="fw-bold text-muted">{{__('admin.profile.school')}}</span>
                            <span class="text-end">{{ $user->profile?->asal_sekolah ?? '-' }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="fw-bold text-muted">{{__('admin.profile.hp')}}</span>
                            <span class="text-end">{{ $user->profile?->nomor_hp ?? '-' }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="fw-bold text-muted">{{__('admin.profile.email')}}</span>
                            <span class="text-end">{{ $user->email }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection

