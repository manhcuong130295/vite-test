<header class="app-header">
    <nav class="navbar navbar-expand-lg navbar-light align-items-center mt-3 px-3 mx-2">
      <a class="sidebar-link" href="{{ route('payment.list') }}" aria-expanded="false" style="position: absolute; right: 100px;">
        <button id="upgrade-btn" type="button" class="btn btn-outline-info m-1 d-flex align-items-center p-2">
          <i id="crown" class="ti ti-crown fs-6 me-1 text-warning"></i>
          Upgrade</button>
      </a>
      <ul class="navbar-nav">
        <li class="nav-item d-block d-xl-none">
          <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
            <i class="ti ti-menu-2"></i>
          </a>
        </li>
      </ul>
      <h4 class="text-primary font-bold d-none d-sm-block">
        Welcome to TOMOGPT {{ auth()->user()->name }}
      </h4>
      <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
        <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
          <li class="nav-item dropdown">
            <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
              aria-expanded="false">
              <img src="{{ auth()->user()->avatar }}" alt="" width="35" height="35" class="rounded-circle">
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
              <div class="message-body">
                <a href="{{ route('profile') }}" class="d-flex align-items-center gap-2 dropdown-item">
                  <i class="ti ti-user fs-6"></i>
                  <p class="mb-0 fs-3">My Profile</p>
                </a>
                <a href="{{ route('logout') }}" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </nav>
  </header>
