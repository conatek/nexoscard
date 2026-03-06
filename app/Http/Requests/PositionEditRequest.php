<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PositionEditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'area_id' => 'required',
            'estimated_salary' => 'required|numeric',
            'criticality_level_id' => 'required',
            'risk_class_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de cargo es requerido',
            'area_id.required' => 'El área es requerida',
            'estimated_salary.required' => 'El salario estimado es requerido',
            'estimated_salary.numeric' => 'El salario estimado debe ser un número',
            'criticality_level_id.required' => 'El nivel de criticidad es requerido',
            'risk_class_id.required' => 'La clase de riesgo es requerida',
        ];
    }
}
