<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserEditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $user = $this->route('user');

        if($this->request->get('image') != "null"){
            return [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'sometimes|min:8',
                'image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|dimensions:max_width=200,max_height=200|max:100',
            ];
        } else {
            return [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'sometimes|min:8',
            ];
        }
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre es requerido.',
            'email.required' => 'El campo email es requerido.',
            'email.email' => 'El campo email no tiene el formato adecuado.',
            'email.unique' => 'El email ya se encuentra registrado.',
            'password.required' => 'El campo contraseña es requerido.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.dimensions' => 'Las dimensiones maximas de imagen son 200 x 200',
            'image.mimes' => 'Formato de imagen no admitido (usar jpeg, jpg, png).',
            'image.max' => 'El archivo excede el tamaño permitido (100KB).',
        ];
    }
}
