<?php

namespace Modules\UserRaffle\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RaffleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:120',
            'draw_date' => 'required',
            'description' => 'required|string',
            'summary' => 'required|string',
            'price' => 'required|min:0',
            'commission_sellers' => 'required|min:0',
            'subscription_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'draw_date.required' => 'El campo fecha de sorteo es obligatorio.',
            'description.required' => 'El campo descripcion es obligatorio.',
            'summary.required' => 'El campo resumen es obligatorio.',
            'price.required' => 'El campo precio es obligatorio.',
            'commission_sellers.required' => 'El campo comisión para el vendedor es obligatorio.',
            'subscription_id.required' => 'El id de la suscripción es un campo obligatorio'
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
