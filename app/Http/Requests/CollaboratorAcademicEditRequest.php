<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollaboratorAcademicEditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if($this->request->get('certificate') != "null"){
            return [
                'achievement_type_id' => 'required',
                'degree' => 'required',
                'institution' => 'required',
                'end_date' => 'required|date',
                'certificate' => 'max:2048|mimes:pdf',
            ];
        } else {
            return [
                'achievement_type_id' => 'required',
                'degree' => 'required',
                'institution' => 'required',
                'end_date' => 'required|date',
            ];
        }
    }

    public function messages(): array
    {
        return [
            'achievement_type_id.required' => 'El tipo de logro es requerido.',
            'degree.required' => 'El título obtenido es requerido.',
            'institution.required' => 'La institución es requerida.',
            'end_date.required' => 'La fecha de finalización es requerida.',
            'end_date.date' => 'Formato de fecha no válido.',
            'certificate.max' => 'El archivo no debe ser mayor a 2MB.',
            'certificate.mimes' => 'El archivo debe ser un PDF.',
        ];
    }
}
