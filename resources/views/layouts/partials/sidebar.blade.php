<aside id="layout-menu" class="layout-menu menu-vertical menu">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-semibold ms-2">IT Helpdesk</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ri-close-line"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item active">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-home-smile-line"></i>
                <div>Dashboard</div>
            </a>
        </li>

        <li class="menu-header small mt-5">
            <span class="menu-header-text">Tiket</span>
        </li>

        @can('view-own-tickets')
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ri ri-ticket-line"></i>
                    <div>Tiket Saya</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <div>Request</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <div>Incident</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        @can('view-assigned-tickets')
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ri ri-file-user-line"></i>
                    <div>Tiket Ditugaskan</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <div>Request</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <div>Incident</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        @can('view-all-tickets')
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ri ri-file-list-3-line"></i>
                    <div>Semua Tiket</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <div>Request</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <div>Incident</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        @can('manage-users')
            <li class="menu-header small mt-5">
                <span class="menu-header-text">Manajemen</span>
            </li>
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ri ri-user-settings-line"></i>
                    <div>Manajemen User</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <div>User</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <div>Role & Permission</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        @can('view-reports')
            <li class="menu-item">
                <a href="#" class="menu-link">
                    <i class="menu-icon icon-base ri ri-file-chart-line"></i>
                    <div>Laporan</div>
                </a>
            </li>
        @endcan
    </ul>
</aside>
