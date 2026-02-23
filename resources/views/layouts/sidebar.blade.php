@push('css')
    <style>
        .nav-sidebar {
            width: 250px;
            background: #f8f9fa;
            border-right: 1px solid #dee2e6;
            height: 100vh;
        }

        .nav-sidebar .nav-link {
            color: #495057;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .nav-sidebar .nav-link:hover {
            background-color: #e9ecef;
            color: #0d6efd;
        }

        .nav-sidebar .nav-link.active {
            color: #0d6efd;
            background-color: #e7f1ff;
            border-right: 3px solid #0d6efd;
        }

        .nav-group-sub {
            border-left: 1px solid #dee2e6;
            margin-left: 20px !important;
        }

        .nav-group-sub-inner {
            border-left: 1px dashed #ced4da;
            margin-left: 15px !important;
        }

        .nav-sidebar i {
            width: 20px;
            text-align: center;
        }
    </style>
@endpush
<!-- Main sidebar -->
<div class="sidebar sidebar-main sidebar-expand-lg">
    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- Main navigation -->
        <div class="sidebar-section">
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <!-- Main -->
                @php
                    $pages = setUserMenu(); 
                    $menus = $pages['menus'];
                    $has_menus = $pages['has_menus'];
                    $administrator = $pages['administrator'];
                @endphp
                @foreach ($menus as $menu)
                    @if (empty($has_menus) && $administrator === false) 
                        @continue
                    @endif
                    @if (!in_array($menu->id, $has_menus) && !empty($has_menus))
                        @continue
                    @endif
                    <li class="nav-item {{ $menu->children->count() && $menu->is_main_section != 1  ? 'nav-item-submenu' : '' }}">
                        <a href="" class="nav-link main-link">
                            @if (!empty($menu->icon))
                                <i class="{{ $menu->icon }} me-2"></i>
                            @endif
                            <span class="fw-bold">{{ $menu->name }}</span>
                        </a>

                        @if (!empty($menu->children) && $menu->children->count() > 0)
                            <ul class="nav flex-column ms-3 nav-group-sub">
                                @foreach ($menu->children as $child)
                                    @if (!empty($has_menus) && !in_array($child->id, $has_menus))
                                        @continue
                                    @endif
                                    
                                    <li class="nav-item {{ $child->children->count() ? 'nav-item-submenu' : '' }}">
                                        <a href="{{ !empty($child->route) ? route($child->route) : '#' }}" class="nav-link {{ request()->routeIs($child->route . '*') ? 'active' : '' }}">
                                            @if (!empty($child->icon))
                                                <i class="{{ $child->icon }} me-2"></i>
                                            @endif
                                            <span>{{ $child->name }}</span>
                                        </a>

                                        @if (!empty($child->children) && $child->children->count() > 0)
                                            <ul class="nav flex-column ms-3 nav-group-sub">
                                                @foreach ($child->children as $grandchild)
                                                    @if (!empty($has_menus) && !in_array($grandchild->id, $has_menus))
                                                        @continue
                                                    @endif
                                                    <li class="nav-item">
                                                        <a href="{{ !empty($grandchild->route) ? route($grandchild->route) : '#' }}" class="nav-link py-1 {{ request()->routeIs($grandchild->route . '*') ? 'active' : '' }}">
                                                            @if (!empty($grandchild->icon))
                                                                <i class="{{ $grandchild->icon }} me-2"></i>
                                                            @endif
                                                            <small>{{ $grandchild->name }}</small>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
                <!-- /page kits -->
            </ul>
        </div>
        <!-- /main navigation -->
    </div>
    <!-- /sidebar content -->
</div>
<!-- /main sidebar -->
