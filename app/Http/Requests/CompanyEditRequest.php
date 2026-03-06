<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyEditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $company = $this->route('company');
        return [
            'company_name' => 'required|unique:companies,company_name,' . $company->id,
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.required' => 'El campo nombre es requerido.',
            'company_name.unique' => 'El nombre ya está registrado.',
        ];
    }
}
