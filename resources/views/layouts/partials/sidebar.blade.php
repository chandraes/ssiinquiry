<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <aside class="app-sidebar">
        <div class="side-header">
            <a class="header-brand1" href="{{route('home')}}">
                <img src="{{ $logoUrl }}" class="header-brand-img desktop-logo" alt="logo">
                <img src="{{ $logoUrl }}" class="header-brand-img toggle-logo" alt="logo">
                <img src="{{ $logoUrl }}" class="header-brand-img light-logo" alt="logo">
                <img src="{{ $logoUrl }}" class="header-brand-img light-logo1" alt="logo">
            </a>
            <!-- LOGO -->
        </div>
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg></div>
            <ul class="side-menu">
                <li class="sub-category">
                    <h3>Main</h3>
                </li>
                <li class="slide">
                    <a class="side-menu__item {{request()->routeIs('home') ? 'active' : ''}}" data-bs-toggle="slide" href="{{route('home')}}"><i
                            class="side-menu__icon fe fe-home"></i><span class="side-menu__label">Dashboard</span></a>
                </li>
                @role(['admin','guru'])
                <li class="slide">
                    <a class="side-menu__item {{request()->routeIs('user') ? 'active' : ''}}" data-bs-toggle="slide" href="{{route('user')}}"><i
                            class="side-menu__icon fe fe-user"></i><span class="side-menu__label">User</span></a>
                </li>

                <li class="slide">
                    <a class="side-menu__item {{request()->routeIs('modul') ? 'active' : ''}}" data-bs-toggle="slide" href="{{route('modul')}}"><i
                            class="side-menu__icon fe fe-file"></i><span class="side-menu__label">Modul</span></a>
                </li>
                @endrole
                {{-- <li class="sub-category">
                    <h3>Widgets</h3>
                </li>
                <li>
                    <a class="side-menu__item" href="widgets.html"><i class="side-menu__icon fe fe-grid"></i><span
                            class="side-menu__label">Widgets</span></a>
                </li>
                <li class="sub-category">
                    <h3>Elements</h3>
                </li>
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i
                            class="side-menu__icon fe fe-database"></i><span
                            class="side-menu__label">Components</span><i class="angle fa fa-angle-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Components</a></li>
                        <li><a href="cards.html" class="slide-item"> Cards design</a></li>
                        <li><a href="calendar.html" class="slide-item"> Default calendar</a></li>
                        <li><a href="calendar2.html" class="slide-item"> Full calendar</a></li>
                        <li><a href="chat.html" class="slide-item"> Default Chat</a></li>
                        <li><a href="notify.html" class="slide-item"> Notifications</a></li>
                        <li><a href="sweetalert.html" class="slide-item"> Sweet alerts</a></li>
                        <li><a href="rangeslider.html" class="slide-item"> Range slider</a></li>
                        <li><a href="scroll.html" class="slide-item"> Content Scroll bar</a></li>
                        <li><a href="loaders.html" class="slide-item"> Loaders</a></li>
                        <li><a href="counters.html" class="slide-item"> Counters</a></li>
                        <li><a href="rating.html" class="slide-item"> Rating</a></li>
                        <li><a href="timeline.html" class="slide-item"> Timeline</a></li>
                        <li><a href="treeview.html" class="slide-item"> Treeview</a></li>
                    </ul>
                </li>
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i
                            class="side-menu__icon fe fe-package"></i><span class="side-menu__label">Elements</span><i
                            class="angle fa fa-angle-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Elements</a></li>
                        <li><a href="alerts.html" class="slide-item"> Alerts</a></li>
                        <li><a href="buttons.html" class="slide-item"> Buttons</a></li>
                        <li><a href="colors.html" class="slide-item"> Colors</a></li>
                        <li><a href="avatarsquare.html" class="slide-item"> Avatar-Square</a></li>
                        <li><a href="avatar-round.html" class="slide-item"> Avatar-Rounded</a></li>
                        <li><a href="avatar-radius.html" class="slide-item"> Avatar-Radius</a></li>
                        <li><a href="dropdown.html" class="slide-item"> Drop downs</a></li>
                        <li><a href="list.html" class="slide-item"> List</a></li>
                        <li><a href="tags.html" class="slide-item"> Tags</a></li>
                        <li><a href="pagination.html" class="slide-item"> Pagination</a></li>
                        <li><a href="navigation.html" class="slide-item"> Navigation</a></li>
                        <li><a href="typography.html" class="slide-item"> Typography</a></li>
                        <li><a href="breadcrumbs.html" class="slide-item"> Breadcrumbs</a></li>
                        <li><a href="badge.html" class="slide-item"> Badges</a></li>
                        <li><a href="panels.html" class="slide-item"> Panels</a></li>
                        <li><a href="thumbnails.html" class="slide-item"> Thumbnails</a></li>
                    </ul>
                </li>
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i
                            class="side-menu__icon fe fe-file"></i><span class="side-menu__label">Advanced
                            Elements</span><i class="angle fa fa-angle-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Advanced Elements</a></li>
                        <li><a href="mediaobject.html" class="slide-item"> Media Object</a></li>
                        <li><a href="accordion.html" class="slide-item"> Accordions</a></li>
                        <li><a href="tabs.html" class="slide-item"> Tabs</a></li>
                        <li><a href="chart.html" class="slide-item"> Charts</a></li>
                        <li><a href="modal.html" class="slide-item"> Modal</a></li>
                        <li><a href="tooltipandpopover.html" class="slide-item"> Tooltip and popover</a></li>
                        <li><a href="progress.html" class="slide-item"> Progress</a></li>
                        <li><a href="carousel.html" class="slide-item"> Carousels</a></li>
                        <li><a href="headers.html" class="slide-item"> Headers</a></li>
                        <li><a href="footers.html" class="slide-item"> Footers</a></li>
                        <li><a href="users-list.html" class="slide-item"> User List</a></li>
                        <li><a href="search.html" class="slide-item">Search</a></li>
                        <li><a href="crypto-currencies.html" class="slide-item"> Crypto-currencies</a></li>
                    </ul>
                </li> --}}

                @role('admin')
                <li class="sub-category">
                    <h3>Pengaturan</h3>
                </li>
                <li>
                    <a class="side-menu__item {{request()->routeIs('admin.settings.*') ? 'active' : ''}}" href="{{route('admin.settings.index')}}"><i class="side-menu__icon fa fa-gears"></i><span
                            class="side-menu__label">Aplikasi</span></a>
                </li>
                @endrole


            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24"
                    height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg></div>
        </div>
    </aside>
</div>
