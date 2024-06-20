<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ url('dashboard') }}">
                 <b>
                    <img src="{{ asset('admin/images/rc-icon-black.png') }}" alt="homepage" class="dark-logo" style="width: 50%;" />
                    <img src="{{ asset('admin/images/rc-icon-white.png') }}" alt="homepage" class="light-logo" style="width: 50%;" />
                 </b>
             </a>
        </div>
        <div class="navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"> <a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                <li class="nav-item"> <a class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark" href="javascript:void(0)"><i class="icon-menu"></i></a> </li>
            </ul>
            <ul class="navbar-nav my-lg-0">
                <li class="nav-item dropdown u-pro">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href="" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{ asset('admin/images/profile.jpg') }}" alt="user" class=""> <span class="hidden-md-down">@if(!empty(user())) {{ user()->first_name .' '. user()->last_name }} @endif &nbsp;<i class="fa fa-angle-down"></i></span> </a>
                    <div class="dropdown-menu dropdown-menu-end animated flipInY">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();  document.getElementById('logout-form').submit();">
                            <i class="fa fa-power-off"></i> {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>