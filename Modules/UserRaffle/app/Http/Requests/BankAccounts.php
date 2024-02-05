<?php

namespace Modules\UserRaffle\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankAccounts extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name_account' => 'required',
            'account_number' => 'required',
            'bank_name' => 'required',
            'type' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name_account.required' => 'El campo nombre de cuenta es obligatorio.',
            'account_number.required' => 'El campo número de cuenta es obligatorio.',
            'bank_name.required' => 'El campo entidad bancaria es obligatorio.',
            'type.required' => 'El campo tipo de cuenta es obligatorio.',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
