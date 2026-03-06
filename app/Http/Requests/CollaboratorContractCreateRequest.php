<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollaboratorContractCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'position_id' => 'required',
            'salary' => 'required|numeric',
            'contract_type_id' => 'required',
            'contract_start_date' => 'required',
            // 'contract_end_date' => 'required',
            'test_period_end_date' => 'required',
            // 'corporate_email' => 'required',
            // 'corporate_cellphone' => 'required',
            'bank_id' => 'required',
            'bank_account' => 'required',
            'eps_id' => 'required',
            'afp_pension_id' => 'required',
            'afp_saving_id' => 'required',
            'arl_id' => 'required',
            'ccf_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'position_id.required' => 'El cargo es requerido.',
            'salary.required' => 'El salario es requerido.',
            'salary.numeric' => 'El salario debe ser un número.',
            'contract_type_id.required' => 'El tipo de contrato es requerido.',
            'contract_start_date.required' => 'La fecha de inicio de contrato es requerida.',
            // 'contract_end_date.required' => 'La fecha de fin de contrato es requerida.',
            'test_period_end_date.required' => 'La fecha de fin de periodo de prueba es requerida.',
            // 'corporate_email.required' => 'El correo corporativo es requerido.',
            // 'corporate_cellphone.required' => 'El celular corporativo es requerido.',
            'bank_id.required' => 'El banco es requerido.',
            'bank_account.required' => 'La cuenta bancaria es requerida.',
            'eps_id.required' => 'La EPS es requerida.',
            'afp_pension_id.required' => 'La AFP de pensiones es requerida.',
            'afp_saving_id.required' => 'La AFP de cesantías es requerida.',
            'arl_id.required' => 'La ARL es requerida.',
            'ccf_id.required' => 'La caja de compensación familiar es requerida.',            
        ];
    }
}
