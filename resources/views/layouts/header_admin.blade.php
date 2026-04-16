<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Garuda DCS | @yield('title')</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/logoupt.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/searchableOptionList.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Select2 Modern Medical Theme -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Select2 Premium Custom Theme Overrides */
        .select2-container--default .select2-selection--single {
            height: 48px !important;
            padding: 10px 14px;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            background-color: #ffffff !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: normal !important;
            color: #1e293b !important;
            font-size: 0.95rem;
            padding-left: 0 !important;
            font-weight: 500;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px !important;
            right: 12px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #64748b transparent transparent transparent !important;
            border-width: 6px 5px 0 5px !important;
        }
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #14b8a6 !important;
            box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.1) !important;
        }
        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #14b8a6 transparent !important;
            border-width: 0 5px 6px 5px !important;
        }
        
        /* Dropdown Results Box */
        .select2-dropdown {
            border: 1px solid #f1f5f9 !important;
            border-radius: 16px !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            overflow: hidden !important;
            padding: 8px !important;
            z-index: 9999 !important;
        }
        .select2-search--dropdown .select2-search__field {
            border: 1px solid #f1f5f9 !important;
            border-radius: 8px !important;
            padding: 8px 12px !important;
            outline: none !important;
            background-color: #f8fafc !important;
        }
        .select2-results__option {
            padding: 10px 12px !important;
            border-radius: 8px !important;
            margin-bottom: 2px !important;
            font-size: 0.9rem !important;
            color: #475569 !important;
        }
        .select2-results__option--highlighted[aria-selected] {
            background-color: #f0fdfa !important;
            color: #14b8a6 !important;
        }
        .select2-results__option--selected {
            background-color: #14b8a6 !important;
            color: white !important;
        }
        
        /* Fix for wide dropdowns (e.g. filters) */
        .select2-container { width: 100% !important; }
        
        /* Modal Fix */
        .select2-container--open { z-index: 99999 !important; }
    </style>

<style>
    #notificationDropdown {
        max-height: 400px;
        /* Set a maximum height for the dropdown */
        overflow-y: auto;
        /* Make the dropdown scrollable */
    }

    .dropdown-width {
        width: 400px;
    }

    .notification-item {
        padding: 10px;
        border: 1px solid #ddd;
        margin-bottom: 10px;
    }

    .highlight {
        background-color: #f0f8ff;
    }

    .notification-time {
        font-size: 0.75rem;
        color: gray;
    }

    .my-custom-popup {
        border-radius: 25px !important;
        font-family: 'Arial', sans-serif !important;
    }
</style>

<body>

    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar" data-simplebar>
                <div class="brand-logo d-flex align-items-center justify-content-between px-4 py-3">
                    <a href="/" class="text-nowrap logo-img">
                        <img src="{{ asset('assets/images/logos/sidebar.webp') }}" width="150" alt="Logo" class="sidebar-logo-img" />
                    </a>
                    <div class="sidebartoggler cursor-pointer d-xl-none d-block ms-auto" id="sidebarCollapse">
                        <i class="ti ti-x fs-8 text-muted"></i>
                    </div>
                </div>

                <!-- Unified Professional Sidebar Navigation -->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav" class="px-3">
                        <li class="nav-small-cap mt-4">
                            <span class="hide-menu fw-bolder text-uppercase text-muted letter-spacing-1">Overview</span>
                        </li>
                        
                        <li class="sidebar-item">
                            @php $isDashboard = Str::contains(request()->url(), 'dashboard'); @endphp
                            <a class="sidebar-link rounded-3 {{ $isDashboard ? 'active' : '' }}" href="{{ route('dashboard') }}" aria-expanded="false">
                                <i class="ti ti-layout-dashboard fs-5"></i>
                                <span class="hide-menu fw-semibold">Dashboard</span>
                            </a>
                        </li>

                        @can('administrate')
                            <li class="nav-small-cap mt-4">
                                <span class="hide-menu fw-bolder text-uppercase text-muted letter-spacing-1">Management</span>
                            </li>
                            <li class="sidebar-item">
                                @php $isUsers = Str::contains(request()->url(), 'users'); @endphp
                                <a class="sidebar-link rounded-3 {{ $isUsers ? 'active' : '' }}" href="{{ url('/rbac/users') }}">
                                    <i class="ti ti-users fs-5"></i>
                                    <span class="hide-menu fw-semibold">User Access</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                @php $isRoles = Str::contains(request()->url(), 'roles'); @endphp
                                <a class="sidebar-link rounded-3 {{ $isRoles ? 'active' : '' }}" href="{{ url('/rbac/roles') }}">
                                    <i class="ti ti-user-check fs-5"></i>
                                    <span class="hide-menu fw-semibold">Roles & Permissions</span>
                                </a>
                            </li>
                        @endcan

                        <li class="nav-small-cap mt-4">
                            <span class="hide-menu fw-bolder text-uppercase text-muted letter-spacing-1">E-Document</span>
                        </li>

                        @can('manage-categories')
                            <li class="sidebar-item">
                                @php $isCat = Str::contains(request()->url(), 'categories'); @endphp
                                <a class="sidebar-link rounded-3 {{ $isCat ? 'active' : '' }}" href="{{ route('categories.index') }}">
                                    <i class="ti ti-category-2 fs-5"></i>
                                    <span class="hide-menu fw-semibold">Kategori</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                @php $isClass = Str::contains(request()->url(), 'classifications'); @endphp
                                <a class="sidebar-link rounded-3 {{ $isClass ? 'active' : '' }}" href="{{ route('classifications.index') }}">
                                    <i class="ti ti-list-numbers fs-5"></i>
                                    <span class="hide-menu fw-semibold">Klasifikasi</span>
                                </a>
                            </li>
                        @endcan

                        @can('active-document')
                            <li class="sidebar-item">
                                @php $isActiveDoc = Str::contains(request()->url(), 'active_document'); @endphp
                                <a class="sidebar-link rounded-3 {{ $isActiveDoc ? 'active' : '' }}" href="{{ route('document.active') }}">
                                    <i class="ti ti-file-text fs-5"></i>
                                    <span class="hide-menu fw-semibold">Repository Utama</span>
                                </a>
                            </li>
                        @endcan

                        @can('view-revisions')
                            <li class="sidebar-item">
                                @php $isMyDoc = Str::contains(request()->url(), ['documents/create', 'document_revision']); @endphp
                                <a class="sidebar-link rounded-3 {{ $isMyDoc ? 'active' : '' }}" href="{{ route('document_revision.index') }}">
                                    <i class="ti ti-folder fs-5"></i>
                                    <span class="hide-menu fw-semibold">Penyusunan</span>
                                </a>
                            </li>
                        @endcan

                        @can('view-approval')
                            @if (!auth()->user()->isRole('Kepala-Puskesmas'))
                                <li class="sidebar-item">
                                    @php $isAppr = Str::contains(request()->url(), 'document_approval'); @endphp
                                    <a class="sidebar-link rounded-3 {{ $isAppr ? 'active' : '' }}" href="{{ route('document_approval.index') }}">
                                        <i class="ti ti-checks fs-5"></i>
                                        <span class="hide-menu fw-semibold">Verifikasi</span>
                                    </a>
                                </li>
                            @endif
                        @endcan

                        @can('view-histories')
                            <li class="sidebar-item">
                                @php $isHist = Str::contains(request()->url(), 'document_histories'); @endphp
                                <a class="sidebar-link rounded-3 {{ $isHist ? 'active' : '' }}" href="{{ route('document_histories.index') }}">
                                    <i class="ti ti-history fs-5"></i>
                                    <span class="hide-menu fw-semibold">Log Audit</span>
                                </a>
                            </li>
                        @endcan

                        <li class="nav-small-cap mt-4">
                            <span class="hide-menu fw-bolder text-uppercase text-muted letter-spacing-1">Action</span>
                        </li>
                        <li class="sidebar-item mb-5">
                            <a class="sidebar-link rounded-3 text-danger border-0" href="javascript:void(0);" onclick="handleLogout(event);">
                                <i class="ti ti-logout fs-5"></i>
                                <span class="hide-menu fw-semibold">Keluar Sistem</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Sidebar Custom Professional Styles -->
        <style>
            .left-sidebar {
                border-right: 1px solid #f1f5f9;
                background: #fff;
            }
            .sidebar-logo-img {
                filter: drop-shadow(0 4px 6px rgba(0,0,0,0.02));
            }
            #sidebarnav .nav-small-cap {
                font-size: 0.7rem;
                padding: 10px 14px;
                color: #94a3b8 !important;
            }
            #sidebarnav .sidebar-item {
                margin-bottom: 5px;
            }
            #sidebarnav .sidebar-link {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 10px 16px;
                color: #475569;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                border-left: 4px solid transparent;
            }
            #sidebarnav .sidebar-link i {
                color: #64748b;
                transition: color 0.3s ease;
            }
            #sidebarnav .sidebar-link:hover {
                background: #f0fdfa;
                color: #14b8a6;
            }
            #sidebarnav .sidebar-link:hover i {
                color: #14b8a6;
            }
            #sidebarnav .sidebar-link.active {
                background: linear-gradient(to right, #f0fdfa, #f8fafc);
                color: #14b8a6 !important;
                border-left-color: #14b8a6;
                box-shadow: none;
            }
            #sidebarnav .sidebar-link.active i {
                color: #14b8a6;
            }
            #sidebarnav .sidebar-link.text-danger:hover {
                background: #fff1f2;
                color: #e11d48 !important;
            }
            #sidebarnav .sidebar-link.text-danger:hover i {
                color: #e11d48;
            }
            .letter-spacing-1 { letter-spacing: 0.05em; }
        </style>
            <!-- End Sidebar scroll-->
        </aside>
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <ul class="navbar-nav">
                        <li class="nav-item d-block d-xl-none">
                            <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse"
                                href="javascript:void(0)">
                                <i class="ti ti-menu-2"></i>
                            </a>
                        </li>
                        <!-- Dropdown Notify Section Start -->
                        <li class="nav-item dropdown">
                            <a class="nav-link nav-icon-hover position-relative " href="javascript:void(0)"
                                id="drop" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-bell-ringing"></i>
                                @if (count(auth()->user()->unreadNotifications) > 0)
                                    <span id="unread-notification-count"
                                        class="position-absolute start-100 translate-middle badge rounded-pill bg-admin">
                                        {{ count(auth()->user()->unreadNotifications) }}
                                        <span class="visually-hidden">unread messages</span>
                                    </span>
                                @endif
                            </a>
                            <div class="p-1 dropdown-menu dropdown-menu-end dropdown-menu-animate-up start-50 dropdown-width"
                                aria-labelledby="drop" id="notificationDropdown">
                                <div class="notification-body">
                                    <div
                                        class="px-3 py-3 d-flex justify-content-between align-items-center border-bottom">
                                        <h6 class="mb-0 fw-semibold">Notifikasi</h6>
                                        <a href="javascript:void()" id="mark-read"
                                            class="badge bg-light-primary text-primary text-decoration-none">
                                            <i class="ti ti-check fs-4"></i> Tandai Semua
                                        </a>
                                    </div>

                                    <!-- Notify Start -->
                                    <div id="notification-list" class="notification-container"
                                        style="max-height: 400px; overflow-y: auto;">
                                        @if (count(auth()->user()->unreadNotifications) > 0)
                                            @foreach (auth()->user()->unreadNotifications->sortByDesc('created_at')->take(5) as $notification)
                                                <div id="notify-items"
                                                    class="p-3 dclose notification-item border-bottom"
                                                    data-notification-id="{{ $notification->id }}"
                                                    style="background: #f8f9ff; cursor: pointer; transition: all 0.3s ease;"
                                                    onmouseover="this.style.background='#eef2ff'"
                                                    onmouseout="this.style.background='#f8f9ff'">
                                                    <a href="{{ $notification->data['link'] }}"
                                                        class="text-dark text-decoration-none d-block">
                                                        <div class="gap-2 d-flex align-items-start">
                                                            <div class="flex-shrink-0">
                                                                <span
                                                                    class="p-2 badge bg-light-primary text-primary rounded-circle">
                                                                    <i class="ti ti-bell fs-5"></i>
                                                                </span>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <p class="mb-1 fw-medium">
                                                                    {{ $notification->data['message'] }}</p>
                                                                <small
                                                                    class="gap-1 text-muted d-flex align-items-center">
                                                                    <i class="ti ti-clock"
                                                                        style="font-size: 14px;"></i>
                                                                    {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="py-5 text-center">
                                                <i class="ti ti-bell-off" style="font-size: 48px; color: #ddd;"></i>
                                                <p class="mt-2 mb-0 text-muted">Tidak Ada Notifikasi Baru</p>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Notify End -->

                                    <div class="p-3 text-center border-top">
                                        <a href="{{ route('notifications') }}"
                                            class="btn btn-sm btn-light-primary text-primary dclose fw-semibold"
                                            id="view-all-notifications">
                                            Lihat Semua Notifikasi
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- Dropdown Notify Section End -->
                    </ul>
                    <div class="px-0 navbar-collapse justify-content-end" id="navbarNav">
                        <ul class="flex-row navbar-nav ms-auto align-items-center justify-content-end">
                            <li class="nav-item dropdown">
                                <a class="nav-link nav-icon-hover-admin" href="{{ route('profile') }}">
                                    <img src="{{ asset('assets/images/profile/user-1.jpg') }}" alt=""
                                        width="35" height="35" class="rounded-circle">
                                </a>

                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!--  Header End -->
