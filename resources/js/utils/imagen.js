/*
 * Formato de salida de los recortes de imagen.
 *
 * El recortador entrega un canvas y antes se exportaba siempre a JPEG 0.85. JPEG no
 * tiene canal alfa, asi que una imagen con el fondo quitado (PNG o WebP) salia con
 * el fondo relleno de negro. Ahora el formato sigue al del archivo original: PNG y
 * WebP se exportan como PNG y conservan la transparencia; el resto sigue en JPEG.
 *
 * El PNG pesa mas que el JPEG. Para que no supere el limite de subida de PHP (2 MB)
 * el canvas se acota a 1024 px con LIENZO_MAX, igual que el logo de la empresa.
 */

export const LIENZO_MAX = { maxWidth: 1024, maxHeight: 1024 }

/**
 * @param {string|null} tipo  MIME del archivo original (file.type)
 * @returns {boolean}
 */
export function conservaAlfa(tipo) {
    return ['image/png', 'image/webp'].includes(tipo)
}

/**
 * Tipo de una imagen ya guardada, deducido de la extension de su URL. Sirve para
 * volver a recortar una imagen existente sin haber elegido un archivo nuevo.
 *
 * @param {string|null} url
 * @returns {string}
 */
export function tipoDeUrl(url) {
    if (!url) return 'image/jpeg'
    const ext = url.split('?')[0].split('.').pop().toLowerCase()
    if (ext === 'png') return 'image/png'
    if (ext === 'webp') return 'image/webp'
    return 'image/jpeg'
}

/**
 * Exporta el canvas del recorte respetando la transparencia del original.
 *
 * @param {HTMLCanvasElement} canvas
 * @param {string|null} tipo  MIME del archivo original
 * @param {string} nombre     nombre sin extension ('photo', 'product'...)
 * @returns {Promise<File>}
 */
export function exportarRecorte(canvas, tipo, nombre) {
    const alfa = conservaAlfa(tipo)
    const mime = alfa ? 'image/png' : 'image/jpeg'
    return new Promise((resolve) => {
        canvas.toBlob((blob) => {
            resolve(new File([blob], `${nombre}.${alfa ? 'png' : 'jpg'}`, { type: mime }))
        }, mime, 0.85)
    })
}
