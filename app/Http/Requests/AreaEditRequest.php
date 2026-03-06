<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AreaEditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'campus_id' => 'required',
            'leader_id' => 'required',
            'name' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'campus_id.required' => 'La sede es requerida.',
            'leader_id.required' => 'El líder de área es requerido.',
            'name.required' => 'El nombre de área es requerido.',
        ];
    }
}
