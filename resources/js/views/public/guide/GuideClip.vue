<template>
    <video
        ref="video"
        class="guide-clip"
        :poster="`/images/guia/${name}.webp`"
        :aria-label="alt"
        muted
        loop
        playsinline
        preload="none"
        :controls="!motionOk"
        width="1920"
        height="1152"
    >
        <source :src="`/images/guia/${name}.mp4`" type="video/mp4">
    </video>
</template>

<script>
/**
 * Captura animada de la guía: un MP4 corto que se comporta como un GIF (sin sonido y en bucle).
 * Se descarga y reproduce solo cuando entra en pantalla y se pausa al salir, para que la guía no
 * baje los diez videos de golpe. Con «reducir movimiento» no se reproduce sola: queda el póster
 * con los controles del navegador.
 */
export default {
    name: 'GuideClip',

    props: {
        name: { type: String, required: true },
        alt:  { type: String, required: true },
    },

    data() {
        return {
            motionOk: !window.matchMedia('(prefers-reduced-motion: reduce)').matches,
        };
    },

    mounted() {
        if (!this.motionOk) return;
        this.observer = new IntersectionObserver(([entry]) => {
            const v = this.$refs.video;
            if (!v) return;
            if (entry.isIntersecting) {
                v.play().catch(() => {});
            } else {
                v.pause();
            }
        }, { threshold: 0.35 });
        this.observer.observe(this.$el);
    },

    beforeUnmount() {
        this.observer?.disconnect();
    },
};
</script>

<style scoped>
.guide-clip {
    display: block;
    width: 100%;
    height: auto;
    aspect-ratio: 5 / 3;
    border-radius: 16px;
    background: #f1f4f6;
}
</style>
