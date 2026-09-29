<template>
    <LegalLayout title="Términos y Condiciones" updated="septiembre de 2026">
        <p>
            Estos Términos y Condiciones regulan el uso de NEXOS Card, la plataforma para crear,
            editar y compartir tarjetas de contacto digitales e interactivas. Al registrarte o usar
            nuestros servicios aceptas estos términos. Si no estás de acuerdo con ellos, te pedimos
            no utilizar la plataforma.
        </p>

        <h2>1. El servicio</h2>
        <p>
            NEXOS Card permite a profesionales, negocios y empresas publicar una tarjeta digital con
            su información de contacto, redes sociales, productos, servicios, ubicación y video, y
            compartirla mediante un enlace o un código QR. Las personas que reciben la tarjeta no
            necesitan instalar ninguna aplicación para verla.
        </p>

        <h2>2. Registro y cuenta</h2>
        <ul>
            <li>Para crear una tarjeta debes registrarte con un nombre y un correo electrónico válidos.</li>
            <li>Eres responsable de mantener la confidencialidad de tu contraseña y de toda actividad realizada desde tu cuenta.</li>
            <li>Debes informarnos de inmediato si detectas un uso no autorizado de tu cuenta.</li>
        </ul>

        <h2>3. Prueba gratuita</h2>
        <p>
            Al registrarte obtienes un periodo de prueba gratuito con acceso a todas las funciones del
            plan. No solicitamos tarjeta de crédito para iniciarlo y no se realiza ningún cobro
            automático al terminar. Si al finalizar la prueba decides no suscribirte, tu tarjeta
            dejará de estar visible públicamente.
        </p>

        <h2>4. Suscripción y pagos</h2>
        <ul>
            <li>El plan se contrata por periodos anuales, al precio vigente publicado en la plataforma al momento del pago.</li>
            <li>Los pagos se procesan a través de Mercado Pago. NEXOS Card no almacena los datos de tu tarjeta ni de tus medios de pago.</li>
            <li>Antes del vencimiento te enviaremos avisos por correo electrónico para que puedas renovar tu suscripción.</li>
            <li>No existen cláusulas de permanencia: puedes dejar de renovar cuando quieras.</li>
        </ul>

        <h2>5. Vencimiento de la suscripción</h2>
        <p>
            Si la suscripción vence y no se renueva, tras un breve periodo de gracia la tarjeta deja de
            estar disponible públicamente. Conservarás el acceso a tu panel para consultar tu
            información y renovar en cualquier momento; al hacerlo, la tarjeta vuelve a estar en línea.
        </p>

        <h2>6. Contenido del usuario</h2>
        <ul>
            <li>Eres el único responsable de la información, imágenes, enlaces y videos que publiques en tu tarjeta.</li>
            <li>Declaras contar con los derechos necesarios sobre ese contenido, incluidas marcas, logotipos y fotografías.</li>
            <li>No está permitido publicar contenido ilegal, engañoso, ofensivo o que infrinja derechos de terceros.</li>
            <li>NEXOS Card podrá suspender o retirar tarjetas que incumplan estas condiciones.</li>
        </ul>

        <h2>7. Dirección de la tarjeta</h2>
        <p>
            Cada tarjeta se publica bajo una dirección propia dentro de nuestro dominio. Algunas
            direcciones están reservadas para el funcionamiento de la plataforma y no pueden
            asignarse a una empresa.
        </p>

        <h2>8. Disponibilidad del servicio</h2>
        <p>
            Trabajamos para que tu tarjeta esté disponible en todo momento, pero pueden presentarse
            interrupciones por mantenimiento, actualizaciones o causas ajenas a nuestro control. NEXOS
            Card no será responsable por perjuicios derivados de estas interrupciones.
        </p>

        <h2>9. Propiedad intelectual</h2>
        <p>
            La plataforma, sus plantillas, diseños y marca son propiedad de NEXOS Card. El uso del
            servicio no otorga ningún derecho sobre ellos más allá de lo necesario para publicar tu
            tarjeta.
        </p>

        <h2>10. Tratamiento de datos</h2>
        <p>
            El tratamiento de la información personal se rige por nuestra
            <router-link to="/politica-privacidad">Política de Privacidad</router-link>.
        </p>

        <h2>11. Cambios a estos términos</h2>
        <p>
            NEXOS Card podrá actualizar estos Términos y Condiciones cuando sea necesario. La versión
            vigente estará disponible en esta misma página.
        </p>

        <h2>12. Legislación aplicable</h2>
        <p>
            Estos términos se rigen por las leyes de la República de Colombia.
        </p>

        <h2>13. Contacto</h2>
        <p>
            Para consultas relacionadas con estos Términos y Condiciones puedes comunicarte con NEXOS
            Card a través de los canales de contacto disponibles en nuestra página.
        </p>
    </LegalLayout>
</template>

<script>
import LegalLayout from '../../components/legal/LegalLayout.vue';

export default {
    name: 'TermsConditions',
    components: { LegalLayout },
};
</script>
