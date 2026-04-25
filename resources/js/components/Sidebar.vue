<template>
    <div class="app-sidebar" :class="{ 'collapsed': !isCollapsed }" @mouseover="hoverSidebar(true)" @mouseleave="hoverSidebar(false)">
        <div class="sidebar-inner">
            <!-- Logo Section -->
            <div class="sidebar-brand">
                <div class="brand-logo">
                    <svg class="logo-icon" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="40" height="40" rx="10" fill="url(#sidebar-logo-gradient)"/>
                        <path d="M12 20C12 15.58 15.58 12 20 12C24.42 12 28 15.58 28 20C28 24.42 24.42 28 20 28" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                        <circle cx="20" cy="20" r="4" fill="white"/>
                        <defs>
                            <linearGradient id="sidebar-logo-gradient" x1="0" y1="0" x2="40" y2="40">
                                <stop stop-color="#a855f7"/>
                                <stop offset="1" stop-color="#ec4899"/>
                            </linearGradient>
                        </defs>
                    </svg>
                    <span class="brand-text">NexosCard</span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="sidebar-nav">
                <ul class="nav-menu">
                    <li class="nav-item" :class="{ 'active': $route.path === '/' }">
                        <router-link to="/" class="nav-link">
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                                    <rect x="14" y="14" width="7" height="7" rx="1"/>
                                </svg>
                            </span>
                            <span class="nav-text">Panel de Control</span>
                        </router-link>
                    </li>

                    <li class="nav-item" :class="{ 'active': $route.path.startsWith('/empresas') }">
                        <router-link to="/empresas" class="nav-link">
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 21h18"/>
                                    <path d="M5 21V7l8-4v18"/>
                                    <path d="M19 21V11l-6-4"/>
                                    <path d="M9 9v.01M9 12v.01M9 15v.01M9 18v.01"/>
                                </svg>
                            </span>
                            <span class="nav-text">Mis Empresas</span>
                        </router-link>
                    </li>
                </ul>
            </nav>

            <!-- Decorative Elements -->
            <div class="sidebar-decoration">
                <div class="decoration-circle circle-1"></div>
                <div class="decoration-circle circle-2"></div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        isCollapsed: {
            type: Boolean,
            required: true,
        },
        sidebarStatus: {
            type: Boolean,
            required: true,
        },
    },
    methods: {
        hoverSidebar(status) {
            this.$emit("updateSidebar", status);
        },
    },
};
</script>

<style scoped>
.app-sidebar {
    width: 280px;
    min-width: 280px;
    height: 100%;
    background: linear-gradient(180deg, #1e1b4b 0%, #312e81 50%, #4c1d95 100%);
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    flex-shrink: 0;
    /* Override base.css */
    margin-top: 0 !important;
    padding-top: 0 !important;
    flex: none !important;
}

.app-sidebar.collapsed {
    width: 80px;
    min-width: 80px;
}

.app-sidebar.collapsed:hover {
    width: 280px;
    min-width: 280px;
}

.sidebar-inner {
    display: flex;
    flex-direction: column;
    height: 100%;
    position: relative;
    z-index: 1;
    width: 100%;
    padding: 0 !important;
}

/* Brand Section */
.sidebar-brand {
    padding: 1rem 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.brand-logo {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.logo-icon {
    width: 40px;
    height: 40px;
    flex-shrink: 0;
}

.brand-text {
    font-size: 1.25rem;
    font-weight: 700;
    color: white;
    white-space: nowrap;
    transition: opacity 0.2s;
}

.app-sidebar.collapsed .brand-text {
    opacity: 0;
    width: 0;
}

.app-sidebar.collapsed:hover .brand-text {
    opacity: 1;
    width: auto;
}

/* Navigation */
.sidebar-nav {
    flex: 1;
    padding: 0.75rem 0.5rem;
    overflow-y: auto;
}

.nav-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.nav-item {
    position: relative;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 0.75rem;
    border-radius: 8px;
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}

.nav-link::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(168, 85, 247, 0.4), rgba(236, 72, 153, 0.4));
    opacity: 0;
    transition: opacity 0.2s;
    border-radius: 10px;
}

.nav-link:hover {
    color: white;
}

.nav-link:hover::before {
    opacity: 1;
}

.nav-item.active .nav-link {
    color: white;
    background: linear-gradient(135deg, rgba(168, 85, 247, 0.5), rgba(236, 72, 153, 0.5));
    box-shadow: 0 4px 15px rgba(168, 85, 247, 0.3);
}

.nav-item.active .nav-link::before {
    opacity: 0;
}

.nav-icon {
    width: 22px;
    height: 22px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 1;
}

.nav-icon svg {
    width: 100%;
    height: 100%;
}

.nav-text {
    font-size: 0.95rem;
    font-weight: 500;
    white-space: nowrap;
    position: relative;
    z-index: 1;
    transition: opacity 0.2s;
}

/* Collapsed State */
.app-sidebar.collapsed .nav-link {
    justify-content: center;
    padding: 0.75rem;
}

.app-sidebar.collapsed .nav-text {
    opacity: 0;
    width: 0;
    overflow: hidden;
}

.app-sidebar.collapsed:hover .nav-link {
    justify-content: flex-start;
    padding: 0.75rem 0.75rem;
}

.app-sidebar.collapsed:hover .nav-text {
    opacity: 1;
    width: auto;
}

/* Decorative Elements */
.sidebar-decoration {
    position: absolute;
    inset: 0;
    pointer-events: none;
    overflow: hidden;
}

.decoration-circle {
    position: absolute;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(168, 85, 247, 0.15), rgba(236, 72, 153, 0.15));
}

.circle-1 {
    width: 200px;
    height: 200px;
    bottom: -50px;
    left: -50px;
}

.circle-2 {
    width: 150px;
    height: 150px;
    top: 30%;
    right: -60px;
}

/* Scrollbar */
.sidebar-nav::-webkit-scrollbar {
    width: 4px;
}

.sidebar-nav::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar-nav::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 2px;
}

.sidebar-nav::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}
</style>
