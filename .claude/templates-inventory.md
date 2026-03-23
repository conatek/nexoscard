# Inventario de Plantillas - MuyLocal

## Resumen Ejecutivo

| Plantilla | Secciones | Controles | Complejidad | Estado |
|-----------|-----------|-----------|-------------|--------|
| Modern    | 7         | ~45       | Alta        | Completa |
| Classic   | 4         | ~10       | Baja        | Completa |
| Minimal   | 3         | ~10       | Baja        | Completa |
| Creative  | 6         | ~15       | Media       | Completa |
| Cyber     | 4         | ~10       | Media       | Completa |
| Vibrant   | 4         | ~12       | Media       | Completa |

---

## 1. Modern (TemplateModern.vue)

**Descripción:** Diseño limpio y profesional con gradientes sutiles.

**Secciones del Schema:**
- `general`: fontFamily, colorFondo, colorFuente
- `header`: contenido, anchoLogo, logoBorde, logoColorBorde, logoTipoBorde, logoRedondeo, colorFuente, tamanoFuente
- `photoBackground`: tipo, color1, color2, direccion, mostrarPatron, patron
- `photo`: mostrar, tamano, radio, tamanoBorde, colorBorde, tipoBorde, sombra
- `profile`: colorFondo, colorBorde, tipoBorde, tamanoBorde, radio, nombreTamano, nombreColor, nombrePeso, apellidoTamano, apellidoColor, apellidoPeso, cargoTamano, cargoColor, cargoPeso, sombra
- `social`: colorIcono, radio, sombra, fondoFacebook, fondoInstagram, fondoTwitter, fondoLinkedin, fondoYoutube, fondoWhatsapp, fondoEmail, fondoCelular, fondoWeb, fondoUbicacion
- `accordion`: colorFuente, colorFuenteEnlace, pesoFuenteEnlace, tamanoFuenteEnlace, colorCuerpo, colorBorde, tipoBorde, tamanoBorde, radio, sombra, colorSeccion1-5
- `footer`: tipoFondo, color1, color2, direccion, mostrarPatron, patron, alturaMinima, colorFuente, tamanoFuente, pesoFuente, colorBorde, tipoBorde, tamanoBorde

**Características especiales:**
- Acordeón con secciones colapsables
- Control granular de cada elemento
- Fondos con patrones opcionales

---

## 2. Classic (TemplateClassic.vue)

**Descripción:** Estilo tradicional y elegante con tipografías serif.

**Secciones del Schema:**
- `general`: colorFondo (#fdfbf7), colorTexto (#2c3e50), colorAcento (#b89947), fuenteTitulos
- `avatar`: mostrar, tamano, radio
- `perfil`: alineacion (left/center/right)
- `botones`: estilo (solido/borde/minimalista)

**Características especiales:**
- Bordes dobles elegantes
- Separadores decorativos
- Tipografías serif (Playfair Display, Merriweather, Lora)
- Foto siempre centrada

---

## 3. Minimal (TemplateMinimal.vue)

**Descripción:** Diseño limpio, enfocado en el contenido y la legibilidad.

**Secciones del Schema:**
- `general`: colorFondo (#ffffff), colorTexto (#1a1a1a), colorAcento (#888888), tipografia, alineacion
- `avatar`: mostrar, tamano, escalaGrises, radio
- `botones`: estilo (fantasma/contorno/solido)

**Características especiales:**
- Filtro blanco y negro opcional para foto
- Diseño ultra-limpio sin decoraciones
- Logo y foto siempre centrados
- Alineación por defecto: center

---

## 4. Creative (TemplateCreative.vue)

**Descripción:** Diseño moderno con efecto glassmorphism y luces ambientales.

**Secciones del Schema:**
- `general`: colorFondo (#0f172a), colorAcento (#38bdf8), fuentePrincipal
- `header`: alturaLogo
- `hero`: mostrarFondoEfecto, colorLuzSecundaria, radioBorde
- `glass`: opacidad, desenfoque
- `avatar`: mostrar, tamano, grosorBorde
- `profile`: nombreTamano, nombreColor, cargoTamano
- `acciones`: tamanoBoton, radioBoton

**Características especiales:**
- Efecto glassmorphism con blur
- Luces de fondo animadas
- Borde degradado en avatar
- Diseño tipo tarjeta flotante

---

## 5. Cyber (TemplateCyber.vue)

**Descripción:** Diseño tecnológico con efectos de neón y alto contraste.

**Secciones del Schema:**
- `general`: colorFondo (#0a0a1a), colorTexto (#e2e8f0), fuentePrincipal
- `neon`: colorAcento (#00ffcc), brillo
- `avatar`: mostrar, tamano, radio
- `botones`: estiloBoton (solido/borde), radio

**Características especiales:**
- Efecto terminal con header de ventana
- Cursor parpadeante animado
- Efectos de glow/brillo neón
- Tipografías monospace (Space Mono, Fira Code)

---

## 6. Vibrant (TemplateVibrant.vue)

**Descripción:** Diseño juvenil con degradados animados y formato Bento Box.

**Secciones del Schema:**
- `general`: color1 (#ff00cc), color2 (#333399), tipografia
- `tarjetas`: colorFondo, colorTexto, radio
- `avatar`: mostrar, tamano, animacion (flotar/latir/ninguna)
- `botones`: colorBoton, colorTextoBoton

**Características especiales:**
- Fondo con gradiente animado
- Diseño Bento Box con tarjetas
- Animaciones de avatar (float, pulse)
- Scroll horizontal en productos
- Efectos hover interactivos

---

## Fuentes Google Cargadas

```
Crimson Text, Fira Code, Inter, Lato, Libre Baskerville, Lora,
Merriweather, Montserrat, Open Sans, Outfit, Playfair Display,
Poppins, Quicksand, Roboto, Roboto Mono, Space Grotesk,
Space Mono, Syne
```

---

## Archivos Relacionados

- `config/templates.php` - Schemas de configuración
- `resources/js/components/templates/LivePreview.vue` - Contenedor
- `resources/js/components/templates/Template*.vue` - Componentes
- `resources/views/app.blade.php` - Fuentes Google
- `resources/js/views/settings/TemplateEditor.vue` - Editor visual

---

*Última actualización: Marzo 2026*
