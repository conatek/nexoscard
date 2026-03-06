<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if($this->request->get('logo') != "null"){
            return [
                'logo' => 'image|mimes:jpeg,png,jpg|dimensions:max_width=200,max_height=200|max:100',
                'company_type_id' => 'required',
                'company_name' => 'required|unique:companies',
                'identification_type_id' => 'required',
                'identification_number' => 'required',
                'province_id' => 'required',
                'city_id' => 'required',
                'industry_type_id' => 'required',
                'size' => 'required|numeric',
            ];
        } else {
            return [
                'company_type_id' => 'required',
                'company_name' => 'required|unique:companies',
                'identification_type_id' => 'required',
                'identification_number' => 'required',
                'province_id' => 'required',
                'city_id' => 'required',
                'industry_type_id' => 'required',
                'size' => 'required|numeric',
            ];
        }
        
    }

    public function messages(): array
    {
        return [
            'logo.image' => 'El archivo debe ser una imagen.',
            'logo.mimes' => 'Formato del logo no admitido (usar jpeg, jpg, png).',
            'logo.dimensions' => 'Las dimensiones maximas del logo son 200 x 200',
            'logo.max' => 'El archivo excede el tamaño permitido (100KB).',
            'company_type_id.required' => 'El campo tipo de empresa es requerido.',
            'company_name.required' => 'El campo nombre es requerido.',
            'company_name.unique' => 'El nombre ya está registrado.',
            'identification_type_id.required' => 'El campo tipo de identificación es requerido.',
            'identification_number.required' => 'El campo número de identificación es requerido.',
            'province_id.required' => 'El campo departamento es requerido.',
            'city_id.required' => 'El campo ciudad es requerido.',
            'industry_type_id.required' => 'El campo sector es requerido.',
            'size.required' => 'El campo cantidad de empleados es requerido.',
            'size.numeric' => 'El campo cantidad de empleados debe ser numérico.',
        ];
    }
}
