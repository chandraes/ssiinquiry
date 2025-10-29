<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <aside class="app-sidebar">

        {{-- HEADER LOGO (Tidak Berubah) --}}
        <div class="side-header">
            <a class="header-brand1" href="{{ route('home') }}">
                <img src="{{ $logoUrl }}" class="header-brand-img desktop-logo" alt="{{ __('admin.logo_alt') }}">
                <img src="{{ $logoUrl }}" class="header-brand-img toggle-logo" alt="{{ __('admin.logo_alt') }}">
                <img src="{{ $logoUrl }}" class="header-brand-img light-logo" alt="{{ __('admin.logo_alt') }}">
                <img src="{{ $logoUrl }}" class="header-brand-img light-logo1" alt="{{ __('admin.logo_alt') }}">
            </a>
        </div>

        {{-- MAIN MENU (Desain Baru) --}}
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg></div>

            <ul class="side-menu">

                {{-- 1. MENU UTAMA (Semua Role) --}}
                <li class="sub-category">
                    <h3>{{ __('admin.sidebar.main') }}</h3>
                </li>
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('home') ? 'active' : '' }}" data-bs-toggle="slide"
                        href="{{ route('home') }}"><i class="side-menu__icon fe fe-home"></i>
                        <span class="side-menu__label">{{ __('admin.sidebar.dashboard') }}</span>
                    </a>
                </li>

                {{-- 2. MENU GURU & ADMIN --}}
                @role(['admin', 'guru'])
                <li class="sub-category">
                    <h3>{{ __('admin.sidebar.management') }}</h3>
                </li>

                {{-- Link ke Gradebook & Manajemen Kelas --}}
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('kelas*') || request()->routeIs('forum.teams*') ? 'active' : '' }}"
                       data-bs-toggle="slide" href="{{ route('kelas') }}">
                       <i class="side-menu__icon fe fe-book-open"></i>
                        <span class="side-menu__label">{{ __('admin.sidebar.management_class') }}</span>
                    </a>
                </li>

                {{-- Link ke Editor Modul & Sub-Modul --}}
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('modul*') || request()->routeIs('submodul*') || request()->routeIs('learning_material*') || request()->routeIs('reflection_question*') || request()->routeIs('practicum_slot*') ? 'active' : '' }}"
                       data-bs-toggle="slide" href="{{ route('modul') }}">
                       <i class="side-menu__icon fe fe-edit"></i>
                        <span class="side-menu__label">{{ __('admin.sidebar.management_module') }}</span>
                    </a>
                </li>

                {{-- Link ke Manajemen Pengguna --}}
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('user*') ? 'active' : '' }}" data-bs-toggle="slide"
                        href="{{ route('user') }}"><i class="side-menu__icon fe fe-users"></i>
                        <span class="side-menu__label">{{ __('admin.sidebar.user') }}</span>
                    </a>
                </li>
                @endrole

                {{-- 3. MENU SISWA --}}
                @role('siswa')
                <li class="sub-category">
                    <h3>{{ __('admin.sidebar.students.learning') }}</h3>
                </li>
                <li class="slide">
                    {{--
                       Link ini akan mengarah ke 'home' yang kemudian oleh HomeController
                       akan diarahkan ke halaman kurikulum ('student.class.show')
                    --}}
                    <a class="side-menu__item {{ request()->routeIs('student.class.show') || request()->routeIs('student.submodule.show') || request()->routeIs('student.class.grades') ? 'active' : '' }}"
                       data-bs-toggle="slide" href="{{ route('home') }}">
                       <i class="side-menu__icon fe fe-book-open"></i>
                        <span class="side-menu__label">{{ __('admin.sidebar.students.my_class') }}</span>
                    </a>
                </li>
                @endrole


                {{-- 4. MENU PENGATURAN (Semua Role) --}}
                <li class="sub-category">
                    <h3>{{ __('admin.sidebar.account') }}</h3>
                </li>
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('profile.*') ? 'active' : '' }}" data-bs-toggle="slide"
                        href="{{ route('profile.index') }}"><i class="side-menu__icon fe fe-user"></i>
                        <span class="side-menu__label">{{ __('admin.sidebar.my_profile') }}</span>
                    </a>
                </li>

                {{-- Khusus Admin --}}
                @role('admin')
                <li class="sub-category">
                    <h3>{{ __('admin.sidebar.settings') }}</h3>
                </li>
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
                        href="{{ route('admin.settings.index') }}"><i class="side-menu__icon fe fe-settings"></i>
                        <span class="side-menu__label">{{ __('admin.sidebar.application') }}</span>
                    </a>
                </li>
                @endrole
            </ul>

            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg></div>
        </div>
    </aside>
</div>
