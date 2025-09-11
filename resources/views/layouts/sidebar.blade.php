<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ url('/') }}" class="brand-link">
            <span class="brand-text fw-light">IFFI-CMS</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation"
                aria-label="Main navigation" data-accordion="true" id="navigation">

                @auth
                    <a href="{{ url('/') }}" class="nav-link active">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                    {{-- Roles && Permissions --}}
                    <li
                        class="nav-item {{ request()->is('user*') || request()->is('role*') || request()->is('permission*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->is('user*') || request()->is('role*') || request()->is('permission*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-clipboard-fill"></i>
                            <p>
                                User Management
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('user.index') }}"
                                    class="nav-link {{ request()->is('user*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>User</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('role.index') }}"
                                    class="nav-link {{ request()->is('role*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Role</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('permission.index') }}"
                                    class="nav-link {{ request()->is('permission*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Permission</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Iffi-sections --}}
                    <li
                        class="nav-item {{ request()->is('ticker*') || request()->is('news-update*') || request()->is('press-release*') || request()->is('latest-update*') || request()->is('photo*') || request()->is('international-media*') || request()->is('peacock*') || request()->is('international-cinema*') || request()->is('indian-panorama*') || request()->is('cube*') || request()->is('master-class*') || request()->is('speaker*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->is('ticker*') || request()->is('news-update*') || request()->is('press-release*') || request()->is('latest-update*') || request()->is('photo*') || request()->is('international-media*') || request()->is('peacock*') || request()->is('international-cinema*') || request()->is('indian-panorama*') || request()->is('cube*') || request()->is('master-class*') || request()->is('speaker*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-clipboard-fill"></i>
                            <p>
                                IFFI-Sections
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        
                        <ul class="nav nav-treeview">
                            
                            {{-- Master Class --}}
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon bi bi-mortarboard"></i>
                                    <p>
                                        Master Class
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview show">
                                    <li class="nav-item">
                                        <a href="{{ route('master-class-date.index') }}" class="nav-link {{ request()->is('master-class-date*') ? 'active' : '' }}">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Dates</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('master-class-topic.index') }}" class="nav-link {{ request()->is('master-class-topic*') ? 'active' : '' }}">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Topics</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('master-class.index') }}" class="nav-link {{ request()->is('master-class*') ? 'active' : '' }}">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Master Class</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('speaker.index') }}" class="nav-link {{ request()->is('speaker*') ? 'active' : '' }}">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Speakers</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            {{-- Repository --}}
                            <li class="nav-item">
                                <a href="{{ route('newsUpdate.popupImage') }}"
                                    class="nav-link {{ request()->is('newsUpdate*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Repository</p>
                                </a>
                            </li>

                            {{-- Ticker --}}
                            <li class="nav-item">
                                <a href="{{ route('ticker.index') }}"
                                    class="nav-link {{ request()->is('ticker*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Ticker</p>
                                </a>
                            </li>

                            {{-- News Update --}}
                            <li class="nav-item">
                                <a href="{{ route('news-update.index') }}"
                                    class="nav-link {{ request()->is('news-update*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>News-Update</p>
                                </a>
                            </li>

                            {{-- Press Release --}}
                            <li class="nav-item">
                                <a href="{{ route('press-release.index') }}" class="nav-link {{ request()->is('press-release*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Press-Release</p>
                                </a>
                            </li>

                            {{-- Latest Update --}}
                            <li class="nav-item">
                                <a href="{{ route('latest-update.index') }}" class="nav-link {{ request()->is('latest-update*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Latest-Update</p>
                                </a>
                            </li>

                            {{-- Gallery --}}
                            <li class="nav-item">
                                <a href="{{ route('photo.index') }}" class="nav-link {{ request()->is('photo*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Gallery</p>
                                </a>
                            </li>

                            {{-- Internation Media --}}
                            <li class="nav-item">
                                <a href="{{ route('international-media.index') }}" class="nav-link {{ request()->is('international-media*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>International Media</p>
                                </a>
                            </li>

                            {{-- Peacock --}}
                            <li class="nav-item">
                                <a href="{{ route('peacock.index') }}" class="nav-link {{ request()->is('peacock*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Peacock</p>
                                </a>
                            </li>

                            {{-- International Cinema --}}
                            <li class="nav-item">
                                <a href="{{ route('international-cinema.index') }}" class="nav-link {{ request()->is('international-cinema*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>International Cinema</p>
                                </a>
                            </li>

                            {{-- Indian Panorama --}}
                            <li class="nav-item">
                                <a href="{{ route('indian-panorama.index') }}" class="nav-link {{ request()->is('indian-panorama*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Indian Panorama</p>
                                </a>
                            </li>

                            {{-- Cube --}}
                            <li class="nav-item">
                                <a href="{{ route('cube.index') }}" class="nav-link {{ request()->is('cube*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Cube</p>
                                </a>
                            </li>
                            
                            {{-- Jury Details --}}
                            <li class="nav-item">
                                <a href="{{ route('jury-detail.index') }}" class="nav-link {{ request()->is('jury-detail*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Jury Details</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Logout --}}
                    <a href="{{ route('logout') }}" class="nav-link">
                        <i class="nav-icon bi bi-clipboard-fill"></i>
                        <p>Logout</p>
                    </a>
                @else
                @endauth
            </ul>
        </nav>
    </div>
</aside>
