<template>
    <!-- <div class="app-sidebar sidebar-shadow collapsed"> -->
    <div class="app-sidebar sidebar-shadow" :class="{ 'collapsed': !isCollapsed }" @mouseover="hoverSidebar(true)" @mouseleave="hoverSidebar(false)">
        <div class="scrollbar-sidebar">
            <div class="app-sidebar__inner">
                <ul class="vertical-nav-menu">
                    <li :class="{ 'mm-active': $route.path === '/' }">
                        <router-link to="/" class="vertical-nav-menu" :class="{ 'mm-active': $route.path === '/' }">
                            <i class="metismenu-icon fa fa-chart-line"></i>
                            <span>Panel de Control</span>
                        </router-link>
                    </li>

                    <li :class="{ 'mm-active': $route.path === '/about' }">
                        <router-link to="/about" class="vertical-nav-menu" :class="{ 'mm-active': $route.path === '/about' }">
                            <i class="metismenu-icon fa fa-chart-line"></i>
                            <span>Sobre Nosotros</span>

                        </router-link>
                    </li>
                </ul>
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
    data() {
        return {
            // sidebarStatus: false,
        };
    },
    methods: {
        // changeSidebarStatus() {
        //     this.sidebarStatus = !this.sidebarStatus;
        // },
        hoverSidebar(status) {
            this.$emit("updateSidebar", status);
        },
    },
};
</script>

<style>
* {
    box-sizing: border-box;
}

html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    overflow: hidden;
}

.app-sidebar {
    width: 280px;
    min-width: 280px;
    transition: width 0.3s ease;
    overflow: hidden;
}

.app-sidebar.collapsed {
    width: 80px;

    min-width: 80px;
    flex: 0 0 80px;
}

.app-sidebar.collapsed:hover {
    width: 280px;
    min-width: 280px;
}

.app-sidebar .scrollbar-sidebar {
    width: inherit;
    min-width: inherit;
    transition: width 0.3s ease;
    background-color: #12b338;
    height: calc(100vh - 60px);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.app-sidebar.collapsed .scrollbar-sidebar {
    width: 80px;
}

.app-sidebar.collapsed:hover .scrollbar-sidebar {
    width: 280px;
}

.app-sidebar__inner {
    flex-grow: 1;
    overflow-y: auto;
}

.vertical-nav-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.vertical-nav-menu li {
    position: relative;
}

.vertical-nav-menu li a {
    display: flex;
    align-items: center;
    line-height: 2.4rem;
    height: 2.4rem;
    padding: 0 1.5rem 0 45px;
    position: relative;
    border-radius: 0.25rem;
    color: #fff;
    text-decoration: none;
    transition: all 0.3s;
    margin: 0.1rem 0;
}

.vertical-nav-menu li a i {
    position: absolute;
    left: 15px;
    font-size: 1.2rem;
}

.vertical-nav-menu li a.mm-active {
    color: #127cb3;
    background: #e0f3ff;
    font-weight: bold;
}

.app-sidebar.collapsed .scrollbar-sidebar .vertical-nav-menu li a {
    padding-left: 20px;
    justify-content: center;
}

.app-sidebar.collapsed:hover .scrollbar-sidebar .vertical-nav-menu li a {
    justify-content: left;
}

.app-sidebar.collapsed .scrollbar-sidebar .vertical-nav-menu li a span {
    display: none;
}

.app-sidebar.collapsed:hover .scrollbar-sidebar .vertical-nav-menu li a span {
    display: inline;
}
</style>
