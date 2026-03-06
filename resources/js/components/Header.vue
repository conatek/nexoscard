<template>
    <!-- <div class="app-header header-shadow" style="border-bottom: 4px solid #ff4600;"> -->
    <div class="app-header header-shadow">
        <div class="app-header__logo">
            <div class="logo-src"></div>
            <div class="header__pane ms-auto">
                <div>
                    <button @click="changeSidebarStatus" type="button" class="hamburger close-sidebar-btn hamburger--elastic" :class="{ 'is-active': !sidebarStatus }" data-class="closed-sidebar">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <div class="app-header__mobile-menu">
            <div>
                <button @click="openSidebarMobile()" type="button" class="hamburger hamburger--elastic mobile-toggle-nav" :class="{ 'is-active': isOpenSidebarMobile }">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
        <div class="app-header__menu">
            <span>
                <!-- <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav"> -->
                <button type="button" class="btn-icon btn-icon-only btn btn-light btn-sm mobile-toggle-header-nav" style="background-color: #fff; border: 1px solid #fff; color: #58a2c9;">
                    <span class="btn-icon-wrapper">
                        <i class="fa fa-ellipsis-v fa-w-6"></i>
                    </span>
                </button>
            </span>
        </div>
        <div class="app-header__content" style="background-color: #fff;">
            <div class="app-header-left"></div>
            <div class="app-header-right">
                <div class="header-btn-lg pe-0">
                    <div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="btn-group">
                                    <a data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                        <!-- @if (auth()->user()->image_url)
                                            <img width="42" class="rounded-circle" src="{{ auth()->user()->image_url }}" alt="">
                                        @else
                                            <img width="42" class="rounded-circle" src="{{ asset('images/default-profile.jpeg') }}" alt="">
                                        @endif -->
                                        <i class="fa fa-angle-down ms-2 opacity-8"></i>
                                    </a>
                                    <div tabindex="-1" role="menu" aria-hidden="true"
                                        class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right">
                                        <div class="dropdown-menu-header">
                                            <div class="dropdown-menu-header-inner bg-info">
                                                <div class="menu-header-image opacity-2" style="background-image: url('images/dropdown-header/city3.jpg');"></div>
                                                <div class="menu-header-content text-start">
                                                    <div class="widget-content p-0">
                                                        <div class="widget-content-wrapper">
                                                            <!-- <div class="widget-content-left me-3">
                                                                @if (auth()->user()->image_url)
                                                                    <img width="42" class="rounded-circle" src="{{ auth()->user()->image_url }}" alt="">
                                                                @else
                                                                    <img width="42" class="rounded-circle" src="{{ asset('images/default-profile.jpeg') }}" alt="">
                                                                @endif
                                                            </div>
                                                            <div class="widget-content-left">
                                                                <div class="widget-heading">{{ auth()->user()->name }}</div>
                                                                <div class="widget-subheading opacity-8">{{ auth()->user()->company->company_name }}</div>
                                                            </div>
                                                            <div class="widget-content-right me-2">
                                                                <button class="btn-pill btn-shadow btn-shine btn btn-focus" onclick="event.preventDefault();
                                                                    document.getElementById('logout-form').submit();">

                                                                    Salir
                                                                </button>

                                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                                    @csrf
                                                                </form>
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content-left  ms-3 header-user-info">
                                <div class="widget-heading">{{ userName }}</div>
                                <div class="widget-subheading">{{ userEmail }}</div>
                            </div>
                            <div class="widget-content-right header-user-info ms-3">
                                <button @click="handleLogout" class="btn btn-sm btn-outline-danger">
                                    <i class="fa fa-sign-out-alt me-1"></i> Salir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header-btn-lg">
                    <button type="button" @click="openRightDrawer()" class="hamburger hamburger--elastic open-right-drawer">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useAuth } from '@/stores/auth';

export default {
    props: {
        isCollapsed: {
            type: Boolean,
            required: true,
        },
        isOpenSidebarMobile: {
            type: Boolean,
            required: false,
        },
    },
    data() {
        return {
            sidebarStatus: false,
        };
    },
    computed: {
        userName() {
            const auth = useAuth();
            return auth.state.user?.name || '';
        },
        userEmail() {
            const auth = useAuth();
            return auth.state.user?.email || '';
        },
    },
    methods: {
        changeSidebarStatus() {
            this.sidebarStatus = !this.sidebarStatus;
            this.$emit('changeSidebarStatus');
        },
        openRightDrawer() {
            this.$emit('openRightDrawer');
        },
        openSidebarMobile() {
            this.$emit('openSidebarMobile');
        },
        async handleLogout() {
            const auth = useAuth();
            await auth.logout();
            this.$router.push({ name: 'login' });
        },
    },
};
</script>
