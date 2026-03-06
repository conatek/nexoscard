<!doctype html>
<html lang="es">
    <head>
        @include('back.partials.back-head')
    </head>
    <body>
        <div id="app">
            <div class="app-container app-theme-green body-tabs-shadow fixed-header fixed-sidebar">
                @include('back.partials.back-header')
                @include('back.partials.back-ui-theme-settings')
                {{-- @include('back.partials.back-main') --}}

                <div class="app-main">
                    <div class="app-sidebar sidebar-shadow">
                        <div class="app-header__logo">
                            <div class="logo-src"></div>
                            <div class="header__pane ms-auto">
                                <div>
                                    <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                                            data-class="closed-sidebar">
                                        <span class="hamburger-box">
                                            <span class="hamburger-inner"></span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="app-header__mobile-menu">
                            <div>
                                <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                                    <span class="hamburger-box">
                                        <span class="hamburger-inner"></span>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="app-header__menu">
                            <span>
                                <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                                    <span class="btn-icon-wrapper">
                                        <i class="fa fa-ellipsis-v fa-w-6"></i>
                                    </span>
                                </button>
                            </span>
                        </div>
                        @canany(['home_master', 'home_super'])
                            @include('back.partials.back-sidebar')
                        @else
                            @include('back.partials.back-sidebar-client')
                        @endcanany

                    </div>

                    <div class="app-main__outer">

                        <div class="app-main__inner">
                            @yield('content')
                        </div>

                        @include('back.partials.back-footer')
                    </div>
                </div>
            </div>
        </div>
        @include('back.partials.back-drawer-wrapper')


        <div class="app-drawer-overlay d-none animated fadeIn"></div>

        {{-- scripts comunes --}}
        @include('back.partials.back-scripts')

        {{-- scripts específicos --}}
        @yield('scripts')

        @vite('resources/js/app.js')
    </body>
</html>
