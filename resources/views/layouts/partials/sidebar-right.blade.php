<div class="d-flex order-lg-2 ms-auto header-right-icons">
    <button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon fe fe-more-vertical text-dark"></span>
    </button>
    <div class="navbar navbar-collapse responsive-navbar p-0">
        <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
            <div class="d-flex order-lg-2">
                <div class="dropdown d-md-flex">
                    <a class="nav-link icon theme-layout nav-link-bg layout-setting">
                        <span class="dark-layout"><i class="fe fe-moon"></i></span>
                        <span class="light-layout"><i class="fe fe-sun"></i></span>
                    </a>
                </div>

                <div class="dropdown d-md-flex">
                    <a class="nav-link icon" data-bs-toggle="dropdown">
                        {{-- Tampilkan bendera aktif --}}
                        @if(app()->getLocale() == 'id')
                            <img src="https://flagcdn.com/id.svg" alt="ID" style="width: 20px; height: auto; border-radius: 3px;">
                        @else
                            <img src="https://flagcdn.com/gb.svg" alt="EN" style="width: 20px; height: auto; border-radius: 3px;">
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <div class="drop-heading">
                            <div class="text-center">
                                <h5 class="text-dark mb-0">{{ __('admin.lang.title') }}</h5>
                            </div>
                        </div>
                        <div class="dropdown-divider m-0"></div>
                        <a class="dropdown-item" href="{{ route('language.switch', 'id') }}">
                            <img src="https://flagcdn.com/id.svg" alt="ID" class="me-2" style="width: 20px;">
                            {{ __('admin.lang.id') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('language.switch', 'en') }}">
                            <img src="https://flagcdn.com/gb.svg" alt="EN" class="me-2" style="width: 20px;">
                            {{ __('admin.lang.en') }}
                        </a>
                    </div>
                </div>
                <div class="dropdown d-md-flex profile-1">
                    <a href="javascript:void(0);" data-bs-toggle="dropdown" class="nav-link leading-none d-flex px-1">
                        <span>
                             @php
                                $fotoPath = auth()->user()->profile?->foto ? 'storage/'. auth()->user()->profile?->foto : 'assets/images/users/default.jpg' ;
                            @endphp
                            {{-- [DIUBAH] alt text --}}
                            <img src="{{asset($fotoPath)}}" alt="{{ __('admin.sidebar_profile.alt') }}"
                                class="avatar  profile-user brround cover-image">
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <div class="drop-heading">
                            <div class="text-center">
                                <h5 class="text-dark mb-0">{{$user->name}}</h5>
                                <small class="text-muted">{{$user->role->name}}</small>
                            </div>
                        </div>
                        <div class="dropdown-divider m-0"></div>
                        <a class="dropdown-item" href="{{route('profile.index')}}">
                            {{-- [DIUBAH] --}}
                            <i class="dropdown-icon fe fe-user"></i> {{ __('admin.sidebar_profile.title') }}
                        </a>

                        @role(['admin'])
                        <a class="dropdown-item" href="{{route('admin.settings.index')}}">
                            {{-- [DIUBAH] --}}
                            <i class="dropdown-icon fe fe-settings"></i> {{ __('admin.sidebar_profile.settings') }}
                        </a>
                        @endrole

                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            {{-- [DIUBAH] --}}
                            <i class="dropdown-icon fe fe-alert-circle"></i> {{ __('admin.sidebar_profile.sign_out') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
