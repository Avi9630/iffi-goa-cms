<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none;"></i>
                </a>
            </li>
            <li class="nav-item dropdown user-menu">
                @auth
                    <a href="{{ url('/') }}" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <span class="d-none d-md-inline">
                            {{ Auth::user()->name }}
                        </span>
                    </a>
                    {{-- <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <li class="user-footer">
                            <a href="{{ route('logout') }}" class="btn btn-default btn-flat float-end">Sign out</a>
                        </li>
                    </ul> --}}
                @else
                    <a href="{{ route('login') }}"
                        class="btn btn-primary btn-flat inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                        Login
                    </a>
                    {{-- @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="btn btn-default btn-flat inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                            Register
                        </a>
                    @endif --}}
                @endauth
            </li>
        </ul>
    </div>
</nav>
