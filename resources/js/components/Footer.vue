<template>
    <div class="app-wrapper-footer">
        <div class="app-footer">
            <div class="app-footer__inner">
                <div class="app-footer-left">
                    <span class="text-muted small">
                        &copy; {{ currentYear }} Nexos - Tarjetas de Presentación Digital
                    </span>
                </div>
                <div class="app-footer-right">
                    <span class="text-muted small">
                        v1.0.0
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AppFooter',
    computed: {
        currentYear() {
            return new Date().getFullYear();
        }
    }
}
</script>

<style scoped>
.app-wrapper-footer {
    position: relative;
    z-index: 10;
}

.app-footer {
    background-color: #ffffff;
    border-top: 1px solid #dee2e6;
    height: 60px;
}

.app-footer__inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 100%;
    padding: 0 1.5rem;
}

.app-footer-left,
.app-footer-right {
    display: flex;
    align-items: center;
}
</style>
