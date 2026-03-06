<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollaboratorFamilyCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'first_surname' => 'required',
            'relationship_id' => 'required',
            'occupation_id' => 'required',
            'sex_type_id' => 'required',
            'birth_date' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es requerido.',
            'first_surname.required' => 'El primer apellido es requerido.',
            'relationship_id.required' => 'El parentesco es requerido.',
            'occupation_id.required' => 'La ocupación es requerida.',
            'sex_type_id.required' => 'El tipo de sexo es requerido.',
            'birth_date.required' => 'La fecha de nacimiento es requerida.',
        ];
    }
}
