<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class EmailHelper
{
    public static function logoBase64(): string
    {
        return Cache::rememberForever('email_logo_base64', function () {
            $path = public_path('images/nexos-logo-email.png');
            if (! file_exists($path)) return '';
            return 'data:image/png;base64,' . base64_encode(file_get_contents($path));
        });
    }
}
