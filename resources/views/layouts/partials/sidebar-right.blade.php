<div class="d-flex order-lg-2 ms-auto header-right-icons">
    <!-- SEARCH -->
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

                <!-- FULL-SCREEN -->
                {{-- <div class="dropdown d-md-flex notifications">
                    <a class="nav-link icon" data-bs-toggle="dropdown"><i class="fe fe-bell"></i><span
                            class=" pulse"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow ">
                        <div class="drop-heading border-bottom">
                            <div class="d-flex">
                                <h6 class="mt-1 mb-0 fs-16 fw-semibold">You have Notification</h6>
                                <div class="ms-auto">
                                    <span class="badge bg-success rounded-pill">3</span>
                                </div>
                            </div>
                        </div>
                        <div class="notifications-menu">
                            <a class="dropdown-item d-flex" href="chat.html">
                                <div class="me-3 notifyimg  bg-primary-gradient brround box-shadow-primary">
                                    <i class="fe fe-message-square"></i>
                                </div>
                                <div class="mt-1 wd-80p">
                                    <h5 class="notification-label mb-1">New review received</h5>
                                    <span class="notification-subtext">2 hours ago</span>
                                </div>
                            </a>
                            <a class="dropdown-item d-flex" href="chat.html">
                                <div class="me-3 notifyimg  bg-secondary-gradient brround box-shadow-primary">
                                    <i class="fe fe-mail"></i>
                                </div>
                                <div class="mt-1 wd-80p">
                                    <h5 class="notification-label mb-1">New Mails Received</h5>
                                    <span class="notification-subtext">1 week ago</span>
                                </div>
                            </a>
                            <a class="dropdown-item d-flex" href="cart.html">
                                <div class="me-3 notifyimg  bg-success-gradient brround box-shadow-primary">
                                    <i class="fe fe-shopping-cart"></i>
                                </div>
                                <div class="mt-1 wd-80p">
                                    <h5 class="notification-label mb-1">New Order Received</h5>
                                    <span class="notification-subtext">1 day ago</span>
                                </div>
                            </a>
                        </div>
                        <div class="dropdown-divider m-0"></div>
                        <a href="javascript:void(0);" class="dropdown-item text-center p-3 text-muted">View all
                            Notification</a>
                    </div>
                </div> --}}

                <!-- MESSAGE-BOX -->
                <div class="dropdown d-md-flex profile-1">
                    <a href="javascript:void(0);" data-bs-toggle="dropdown" class="nav-link leading-none d-flex px-1">
                        <span>
                            @if($user->profile && $user->profile->foto)
                            @php
                                $path = auth()->user()->profile?->foto ? 'storage/'.auth()->user()->profile?->foto : 'assets/images/users/8.jpg';
                            @endphp
                                <img src="{{ asset($path) }}" class="avatar  profile-user brround cover-image" alt="Foto Profil">
                            @else
                                <img src="{{ asset('assets/images/users/default.jpg') }}" class="rounded-circle avatar-lg" alt="Default Foto">
                            @endif
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
                            <i class="dropdown-icon fe fe-user"></i> Profile
                        </a>
                        
                        @role(['admin'])
                        <a class="dropdown-item" href="{{route('admin.settings.index')}}">
                            <i class="dropdown-icon fe fe-settings"></i> Settings
                        </a>
                        @endrole
                        
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            <i class="dropdown-icon fe fe-alert-circle"></i> Sign out
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
                {{-- <div class="dropdown d-md-flex header-settings">
                    <a href="javascript:void(0);" class="nav-link icon " data-bs-toggle="sidebar-right"
                        data-target=".sidebar-right">
                        <i class="fe fe-menu"></i>
                    </a>
                </div> --}}
                <!-- SIDE-MENU -->
            </div>
        </div>
    </div>
</div>
