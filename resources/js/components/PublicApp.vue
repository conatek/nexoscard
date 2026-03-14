<template>
    <!-- Vistas públicas de tarjetas: sin layout de admin, siempre bare -->
    <router-view v-if="isPublicLayout" />

    <!-- Layout con sidebar/header para usuarios autenticados -->
    <div v-else-if="isAuthenticated" class="app-container app-theme-dark fixed-header fixed-sidebar fixed-footer"
        :class="{
            'closed-sidebar': !isCollapsed,
            'sidebar-mobile-open': isOpenSidebarMobile
        }">
        <main-header
            :isCollapsed="isCollapsed"
            :sidebarStatus="sidebarStatus"
            :isOpenSidebarMobile="isOpenSidebarMobile"
            @changeSidebarStatus="sidebarVisualization"
            @openRightDrawer="openRightDrawer"
            @openSidebarMobile="openSidebarMobile"
        />

        <div class="ui-theme-settings" :class="{ 'settings-open': isOpenRightSettings }">
            <button @click="openRightSettings()" type="button" id="TooltipDemo" class="btn-open-options btn" style="background-color: #e288f9">
                <i class="fa fa-cog fa-w-16 fa-spin fa-2x" style="color: black;"></i>
            </button>
            <div class="theme-settings__inner">
                <div class="scrollbar-container">
                </div>
            </div>
        </div>

        <div class="app-main">
            <main-sidebar :isCollapsed="isCollapsed" :sidebarStatus="sidebarStatus" @updateSidebar="toggleSidebar" />

            <div class="app-main__outer">

                <div class="app-main__inner">
                    <router-view />
                </div>

                <main-footer />
            </div>
        </div>

        <div class="app-drawer-wrapper" :class="{ 'drawer-open': isOpenRightDrawer }">
            <div class="drawer-nav-btn">
                <button type="button" @click="closeRightDrawer()" class="hamburger hamburger--elastic is-active">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
            <div class="drawer-content-wrapper">

            </div>
        </div>

        <div @click="closeRightDrawer()" class="app-drawer-overlay animated fadeIn" :class="{ 'd-none': !isOpenRightDrawer }"></div>
    </div>

    <!-- Sin layout para páginas de auth (login/register) -->
    <router-view v-else />
</template>

<script>
import { useAuth } from '@/stores/auth';

export default {
    data() {
        return {
            isCollapsed: true,
            sidebarStatus: false,
            isOpenRightDrawer: false,
            isOpenRightSettings: false,
            isOpenSidebarMobile: false,
        };
    },
    computed: {
        isAuthenticated() {
            const auth = useAuth();
            return auth.isAuthenticated.value;
        },
        isPublicLayout() {
            return this.$route.meta?.layout === 'public';
        },
    },
    methods: {
        sidebarVisualization() {
            this.isCollapsed = !this.isCollapsed;
        },
        toggleSidebar(status) {
            this.sidebarStatus = status;
        },
        openRightDrawer() {
            this.isOpenRightDrawer = true;
        },
        closeRightDrawer() {
            this.isOpenRightDrawer = false;
        },
        openRightSettings() {
            this.isOpenRightSettings = !this.isOpenRightSettings;
        },
        openSidebarMobile() {
            this.isOpenSidebarMobile = !this.isOpenSidebarMobile;
        },
    },
};
</script>

<style>
.closed-sidebar.fixed-footer .app-footer .app-footer__inner {
  margin-left: 80px !important;
}

.fixed-footer .app-footer {
    position: fixed;
    width: 100%;
    bottom: 0;
    left: 0;
    z-index: 7;
}

@media (max-width: 768px) {
    .app-container {
        padding: 0;
    }

    .app-header .app-header__logo {
        order: 2;
        background: transparent !important;
        border: 0 !important;
    }

    .app-header .app-header__logo .header__pane {
        display: none;
    }

    .app-header .app-header__logo {
        order: 2;
        background: transparent !important;
        border: 0 !important;
    }

    .app-header__logo {
        /* padding: 0 1.5rem; */
        padding: 0;
        height: 60px;
        /* max-width: 280px; */
        width: 100%;
        display: flex;
        align-items: center;
        transition: width .2s;

    }
    .app-header__logo .logo-src {
        height: 30px;
        /* width: 200px; */
        width: 100%;
        background: url("../../images/logo-muyhumano-lineal.png");
        background-size: contain;
        background-repeat: no-repeat;
    }

    .app-sidebar {
        transform: translateX(-100%);
        transition: transform 0.5s ease-in-out !important;
    }

    .sidebar-mobile-open .app-sidebar {
        transform: translateX(0);
    }

    .fixed-footer .app-footer .app-footer__inner {
        box-shadow: 0.3rem -0.46875rem 2.1875rem rgba(4, 9, 20, 0.02), 0.3rem -0.9375rem 1.40625rem rgba(4, 9, 20, 0.02), 0.3rem -0.25rem 0.53125rem rgba(4, 9, 20, 0.04), 0.3rem -0.125rem 0.1875rem rgba(4, 9, 20, 0.02);
    }

    .app-footer .app-footer__inner .app-footer-left {
        display: flex;
        align-items: center;
    }
}

@media (min-width: 769px) {
    .app-header__logo {
        padding: 0 1.5rem;
        height: 60px;
        width: 280px;
        display: flex;
        align-items: center;
        transition: width .2s;

    }
    .app-header__logo .logo-src {
        height: 30px;
        width: 100%;
        background: url("../../images/logo-muyhumano-lineal.png");
        background-size: contain;
        background-repeat: no-repeat;
    }

    .fixed-footer .app-footer .app-footer__inner {
        margin-left: 280px;
        box-shadow: 0.3rem -0.46875rem 2.1875rem rgba(4, 9, 20, 0.02), 0.3rem -0.9375rem 1.40625rem rgba(4, 9, 20, 0.02), 0.3rem -0.25rem 0.53125rem rgba(4, 9, 20, 0.04), 0.3rem -0.125rem 0.1875rem rgba(4, 9, 20, 0.02);
    }
}

.fixed-footer .app-main .app-main__outer {
    padding-bottom: 60px;
    height: calc(100vh - 60px);  /* 60px = altura del header */
    overflow-y: auto;
}
</style>
