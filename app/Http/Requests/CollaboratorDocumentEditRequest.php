<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollaboratorDocumentEditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if($this->request->get('document') != "null"){
            return [
                'document_type_id' => 'required',
                'document' => 'max:2048|mimes:pdf',
            ];
        } else {
            return [
                'document_type_id' => 'required',
            ];
        }
    }

    public function messages(): array
    {
        return [
            'document_type_id.required' => 'El tipo de documento es requerido.',
            'document.max' => 'El tamaño de archivo no debe ser mayor a 2MB.',
            'document.mimes' => 'El archivo debe ser un PDF.',
        ];
    }
}
