<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ url('/') }}" class="brand-link">
            <span class="brand-text fw-light">IFFI-CMS</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation"
                aria-label="Main navigation" data-accordion="false" id="navigation">

                @auth
                    <a href="{{ url('/') }}" class="nav-link active">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>

                    {{-- Roles && Permissions --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-clipboard-fill"></i>
                            <p>User Management<i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('user.index') }}" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>User</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('role.index') }}" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Role</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('permission.index') }}" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Permission</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Iffi-sections --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-clipboard-fill"></i>
                            <p>IFFI-Sections<i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('ticker.index') }}" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Ticker</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('news-update.index') }}" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>News-Update</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('press-release.index') }}" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Press-Release</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('latest-update.index') }}" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Latest-Update</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('photo.index') }}" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Gallery</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('international-media.index') }}" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Internationa Media</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('peacock.index') }}" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Peacock</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('international-cinema.index') }}" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>International Cinema</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('cube.index') }}" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Cube</p>
                                </a>
                            </li>
                            {{-- Master Class --}}
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon bi bi-mortarboard"></i>
                                    <p>
                                        Master Class
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('master-class-date.index') }}" class="nav-link">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Dates</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('master-class-topic.index') }}" class="nav-link">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Topics</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('master-class.index') }}" class="nav-link">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Master Class</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('speaker.index') }}" class="nav-link">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Speakers</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    {{-- Logout --}}
                    <a href="{{ route('logout') }}" class="nav-link">
                        <i class="nav-icon bi bi-clipboard-fill"></i>
                        <p>Lagout</p>
                    </a>
                @else
                @endauth
            </ul>
        </nav>
    </div>
</aside>
