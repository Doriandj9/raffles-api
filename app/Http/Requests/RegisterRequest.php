<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        

        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El campo nombre es obligatorio',
            'description.required' => 'El campo descripcion es obligatorio.',
            'number_raffles.required' => 'El campo número de rifas es obligatorio.',
            'price.required' => 'El campo precio es obligatorio.',
            'minimum_tickets.required' => 'El campo mínimo número de tickets es obligatorio.',
            'maximum_tickets.required' => 'El campo máximo número de tickets es obligatorio.',
        ];
    }
}
