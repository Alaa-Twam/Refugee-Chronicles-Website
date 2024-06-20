<header id="header" class="transparent-header floating-header @if(Route::currentRouteName() != 'home' || count(\Sliders::getActiveSliders()) == 0) mt-0 full-background @endif">
    <div id="header-wrap">
        <div class="container">
            <div class="header-row">
                <div id="logo">
                    <a href="{{ url('/') }}">
                        <img class="logo-default" srcset="{{ asset('frontend/images/rc-logo-white.png') }}" alt="RC Logo">
                    </a>
                </div>
                <div class="primary-menu-trigger">
                    <button class="cnvs-hamburger" type="button" title="Open Mobile Menu">
                        <span class="cnvs-hamburger-box"><span class="cnvs-hamburger-inner"></span></span>
                    </button>
                </div>
                <nav class="primary-menu">
                    <ul class="menu-container">
                        <li class="menu-item">
                            <a class="menu-link" href="{{ url('chronicles') }}"><div>Chronicles</div></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ url('about-us') }}"><div>About Us</div></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ url('support-our-work') }}"><div>Support Our Work</div></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <div class="header-wrap-clone"></div>
</header>