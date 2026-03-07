<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class CloudinaryService
{
    /**
     * Sube una imagen a Cloudinary y retorna la URL pública.
     *
     * En producción, usa el SDK oficial: cloudinary/cloudinary_php
     * o el paquete laravel-cloudinary.
     *
     * @return array{url: string, public_id: string}
     */
    public function upload(UploadedFile $file, string $folder = 'general'): array
    {
        // --- Implementación real con SDK ---
        // $result = \Cloudinary\Cloudinary::upload($file->getRealPath(), [
        //     'folder' => $folder,
        //     'transformation' => [['quality' => 'auto', 'fetch_format' => 'auto']],
        // ]);
        // return ['url' => $result->getSecurePath(), 'public_id' => $result->getPublicId()];

        // --- Simulación para desarrollo ---
        $filename = uniqid($folder . '_') . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs("uploads/{$folder}", $filename, 'public');

        return [
            'url'       => asset("storage/{$path}"),
            'public_id' => "uploads/{$folder}/{$filename}",
        ];
    }

    /**
     * Elimina una imagen de Cloudinary por su public_id.
     */
    public function destroy(string $publicId): void
    {
        // \Cloudinary\Cloudinary::destroy($publicId);
    }
}
