<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyId = $this->route('company');

        return [
            'name'        => ['sometimes', 'required', 'string', 'max:150'],
            'slug'        => [
                'sometimes',
                'required',
                'string',
                'max:80',
                'alpha_dash',
                Rule::unique('companies', 'slug')->ignore($companyId),
            ],
            'logo'        => ['nullable', 'image', 'max:5120'],
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
}
