<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            {{-- Dashboard --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard') ? 'active fw-bold text-primary' : '' }}" href="{{ url('dashboard') }}">
                    <span data-feather="home" class="me-2"></span>
                    Dashboard
                </a>
            </li>

            {{-- Postingan --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->is('postingan*') ? 'active fw-bold text-primary' : '' }}" href="{{ url('postingan') }}">
                    <span data-feather="file" class="me-2"></span>
                    Postingan
                </a>
            </li>

            {{-- Hanya admin (role == 1) --}}
            @if (auth()->user()->role == 1)
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('categories*') ? 'active fw-bold text-primary' : '' }}" href="{{ url('categories') }}">
                        <span data-feather="shopping-cart" class="me-2"></span>
                        Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('approval*') ? 'active fw-bold text-primary' : '' }}" href="{{ route('approval.index') }}">
                        <span data-feather="check-circle" class="me-2"></span>
                        Approval Postingan
                    </a>
                </li>
            @endif

            {{-- Users --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->is('users*') ? 'active fw-bold text-primary' : '' }}" href="{{ url('users') }}">
                    <span data-feather="users" class="me-2"></span>
                    Users
                </a>
            </li>

            {{-- Back to Home --}}
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/') }}">
                    <span data-feather="arrow-left" class="me-2"></span>
                    Back To Home
                </a>
            </li>

            {{-- Logout --}}
            <li class="nav-item">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                <a class="nav-link" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span data-feather="log-out" class="me-2"></span>
                    Logout
                </a>
            </li>
        </ul>
    </div>
</nav>
