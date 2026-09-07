<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="padding: 0 16px;">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="color: var(--coffee-dark);">
                <i class="fas fa-bars" style="font-size: 16px;"></i>
            </a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown" href="#" style="padding: 6px 12px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--coffee); display: flex; align-items: center; justify-content: center; font-weight: 700; color: white; font-size: 12px; margin-right: 8px;">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
                <span style="font-weight: 600; font-size: 13px; color: var(--coffee-dark);">{{ Auth::user()->name ?? 'Admin' }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" style="border: none; box-shadow: var(--shadow-lg); border-radius: var(--radius-sm); padding: 6px; min-width: 180px;">
                <a href="{{ route('profile.edit') }}" class="dropdown-item" style="border-radius: var(--radius-xs); padding: 8px 14px; font-size: 13px; font-weight: 500;">
                    <i class="fas fa-user mr-2" style="width: 16px; color: var(--text-muted);"></i> Profil Saya
                </a>
                <div class="dropdown-divider" style="margin: 4px 0;"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item" style="border-radius: var(--radius-xs); padding: 8px 14px; font-size: 13px; font-weight: 500; color: var(--danger); cursor: pointer; width: 100%; text-align: left; border: none; background: none;">
                        <i class="fas fa-sign-out-alt mr-2" style="width: 16px;"></i> Logout
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>
