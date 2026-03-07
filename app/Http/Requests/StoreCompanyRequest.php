<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:150'],
            'slug'        => [
                'required',
                'string',
                'max:80',
                'alpha_dash',               // solo letras, números, guiones y underscores
                Rule::unique('companies', 'slug'),
            ],
            'logo'        => ['nullable', 'image', 'max:5120'],  // 5 MB
            'address'     => ['nullable', 'string', 'max:255'],
            'address_notes' => ['nullable', 'string', 'max:255'],
            'phone'       => ['nullable', 'string', 'max:30'],
            'email'       => ['nullable', 'email', 'max:150'],
            'facebook'    => ['nullable', 'url', 'max:255'],
            'instagram'   => ['nullable', 'url', 'max:255'],
            'twitter'     => ['nullable', 'url', 'max:255'],
            'youtube'     => ['nullable', 'url', 'max:255'],
            'website'     => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],

            // Design settings
            'design_settings'                    => ['nullable', 'array'],
            'design_settings.template'           => ['nullable', 'string', 'in:modern,classic,minimal,bold'],
            'design_settings.primary_color'      => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'design_settings.secondary_color'    => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'design_settings.background_color'   => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'design_settings.text_color'         => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'design_settings.accent_color'       => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'design_settings.font_family'        => ['nullable', 'string', 'max:60'],
            'design_settings.border_radius'      => ['nullable', 'integer', 'min:0', 'max:50'],
            'design_settings.show_services'      => ['nullable', 'boolean'],
            'design_settings.show_products'      => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.alpha_dash' => 'El slug solo puede contener letras, números, guiones (-) y underscores (_).',
            'slug.unique'     => 'Este slug ya está en uso. Elige otro.',
            'design_settings.*.regex' => 'El color debe ser un valor hexadecimal válido (ej: #3B82F6).',
        ];
    }
}
