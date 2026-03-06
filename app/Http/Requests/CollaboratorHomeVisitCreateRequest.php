<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollaboratorHomeVisitCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if($this->request->get('home_visit_report') != "null"){
            return [
                'home_visit_type_id' => 'required',
                'visit_date' => 'required|date',
                'home_visit_report' => 'max:2048|mimes:pdf',
            ];
        } else {
            return [
                'home_visit_type_id' => 'required',
                'visit_date' => 'required|date',
            ];
        }
    }

    public function messages(): array
    {
        return [
            'home_visit_type_id.required' => 'El motivo de visita es requerido.',
            'visit_date.required' => 'La fecha de visita es requerida.',
            'visit_date.date' => 'La fecha de visita no tiene un formato válido.',
            'home_visit_report.max' => 'El archivo no debe ser mayor a 2MB.',
            'home_visit_report.mimes' => 'El archivo debe ser un PDF.',
        ];
    }
}
