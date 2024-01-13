<?php

namespace Modules\Admin\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:80',
            'description' => 'required|string',
            'number_raffles' => 'required|min:1',
            'price' => 'required|min:0',
            'minimum_tickets' => 'required|min:1',
            'maximum_tickets' => 'required'

        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function messages()
    {
        return [
            'title.required' => 'El campo nombre es obligatorio.',
            'description.required' => 'El campo descripcion es obligatorio.',
            'number_raffles.required' => 'El campo número de rifas es obligatorio.',
            'price.required' => 'El campo precio es obligatorio.',
            'minimum_tickets.required' => 'El campo mínimo número de tickets es obligatorio.',
            'maximum_tickets.required' => 'El campo máximo número de tickets es obligatorio.',
        ];
    }
}
