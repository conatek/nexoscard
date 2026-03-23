# Mejoras de UX Identificadas - Sistema de Plantillas

## Análisis de Consistencia

### 1. Inconsistencias en Nomenclatura de Secciones

| Concepto | Modern | Classic | Minimal | Creative | Cyber | Vibrant |
|----------|--------|---------|---------|----------|-------|---------|
| Foto perfil | `photo` | `avatar` | `avatar` | `avatar` | `avatar` | `avatar` |
| Botones | `social` | `botones` | `botones` | `acciones` | `botones` | `botones` |
| Tipografía | `fontFamily` | `fuenteTitulos` | `tipografia` | `fuentePrincipal` | `fuentePrincipal` | `tipografia` |

**Recomendación:** Estandarizar a:
- Foto: `avatar` (ya es mayoría)
- Botones: `botones` (ya es mayoría)
- Tipografía: `tipografia` (más claro en español)

---

### 2. Opciones en Inglés vs Labels en Español

**Problema actual:**
```php
'alineacion' => [
    'label' => 'Alineación',  // Español
    'options' => ['left', 'center', 'right'],  // Inglés
]
```

**Impacto:** Confunde al usuario que ve "left" cuando el label dice "Alineación".

**Solución propuesta:**
```php
'alineacion' => [
    'label' => 'Alineación',
    'options' => ['izquierda', 'centro', 'derecha'],
]
```

**Otros casos:**
- `estilo: ['solido', 'borde', 'minimalista']` - OK, ya en español
- `tipo: ['oculto', 'solido', 'degradado']` - OK
- `animacion: ['flotar', 'latir', 'ninguna']` - OK

---

### 3. Unidades Inconsistentes

| Campo | Modern | Classic | Minimal | Creative | Cyber | Vibrant |
|-------|--------|---------|---------|----------|-------|---------|
| avatar.radio | `px` | `%` | `%` | - | `%` | - |
| botones.radio | `%` | - | - | `%` | `%` | - |

**Recomendación:** Usar `%` para redondeo (más intuitivo: 0%=cuadrado, 50%=círculo).

---

### 4. Plantillas Simplificadas vs Modern

**Modern** tiene ~45 controles, mientras otras tienen ~10-15.

**Controles que faltan en plantillas simples:**
- Control de tamaño de logo
- Control de tamaño de nombre/cargo
- Control de colores de texto individuales
- Control de sombras

**Recomendación:** Agregar controles básicos a todas:
```php
// Mínimo recomendado para cada plantilla
'general' => [
    'colorFondo',
    'colorTexto',
    'colorAcento',
    'tipografia',
],
'header' => [
    'mostrarLogo',
    'tamanoLogo',  // Nuevo
],
'avatar' => [
    'mostrar',
    'tamano',
    'radio',
],
'perfil' => [
    'tamanoNombre',  // Nuevo para algunas
    'tamanoCargo',   // Nuevo para algunas
],
'botones' => [
    'estilo',
    'radio',  // Nuevo para algunas
],
```

---

### 5. Valores por Defecto

**Problemas detectados:**
- `photo.radio` en Modern tiene valor `15` (px), debería ser `50` (%) para círculo
- `colorFuente` vs `colorTexto` - inconsistencia en nomenclatura

---

## Plan de Mejoras Prioritario

### Fase 1: Consistencia Inmediata (Bajo impacto)
- [ ] Cambiar opciones de alineación a español en todos los schemas
- [ ] Documentar convención: `%` para redondeo, `px` para tamaños

### Fase 2: Mejoras de Funcionalidad (Medio impacto)
- [ ] Agregar control de tamaño de logo a Classic, Minimal, Cyber, Vibrant
- [ ] Agregar control de tamaño de nombre a plantillas que no lo tienen
- [ ] Estandarizar sección `avatar` en Modern (cambiar `photo` → `avatar`)

### Fase 3: Refactoring Mayor (Alto impacto)
- [ ] Unificar nomenclatura de secciones en todas las plantillas
- [ ] Crear schema base que todas las plantillas extiendan
- [ ] Migrar datos existentes de empresas a nueva estructura

---

## Notas Técnicas

### Compatibilidad hacia atrás
Al cambiar nombres de campos, considerar:
1. Los datos guardados en `company_settings.customization` usan los nombres actuales
2. Sería necesario una migración de datos
3. Alternativa: mantener ambos nombres y hacer fallback en computed

```javascript
// Ejemplo de fallback para compatibilidad
avatarStyle() {
    // Soportar tanto 'photo' como 'avatar'
    const config = this.customization?.avatar || this.customization?.photo || {}
    return {
        width: `${config.tamano ?? 120}px`,
    }
}
```

---

*Documento de trabajo - Marzo 2026*
