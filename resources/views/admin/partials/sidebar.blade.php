<aside class="left-sidebar">
    <div class="scroll-sidebar">
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="user-pro"> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><img src="{{ asset('admin/images/profile.jpg') }}" alt="user-img" class="img-circle"><span class="hide-menu">@if(!empty(user())) {{ user()->first_name .' '. user()->last_name }} @endif</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{ route('logout') }}"
                           onclick="event.preventDefault();  document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i>  {{ __('Logout') }}</a></li>
                    </ul>
                </li>
                <li>
                    <a class="waves-effect waves-dark" href="{{ route('dashboard') }}" aria-expanded="false"><i class="icon-speedometer"></i><span class="hide-menu">Dashboard</span></a>
                </li>
                @if(!empty(isSuperuser()) || (!empty(user()) && user()->can('User::admin.view') || user()->can('User::role.view')))
                    <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="icon-user"></i><span class="hide-menu">Users</span></a>
                        <ul aria-expanded="false" class="collapse">
                            @if(isSuperuser() || user()->can('User::admin.view'))
                                <li><a href="{{ url('users') }}">Users</a></li>
                            @endif
                            @if(isSuperuser() || user()->can('User::role.view'))
                                <li><a href="{{ url('roles') }}">Roles</a></li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if(!empty(isSuperuser()) || (!empty(user()) && user()->can('CMS::chronicle.view')))
                    <li>
                        <a class="waves-effect waves-dark" href="{{ url('cms/chronicles') }}" aria-expanded="false"><i class="fa fa-newspaper"></i><span class="hide-menu">Chronicles</span></a>
                    </li>
                @endif
                @if(!empty(isSuperuser()) || (!empty(user()) && user()->can('Slider::slider.view')))
                    <li>
                        <a class="waves-effect waves-dark" href="{{ url('sliders') }}" aria-expanded="false"><i class="fa fa-sliders-h"></i><span class="hide-menu">Sliders</span></a>
                    </li>
                @endif
                @if(!empty(isSuperuser()) || (!empty(user()) && user()->can('City::city.view')))
                    <li>
                        <a class="waves-effect waves-dark" href="{{ url('cities') }}" aria-expanded="false"><i class="fa fa-city"></i><span class="hide-menu">Cities</span></a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>