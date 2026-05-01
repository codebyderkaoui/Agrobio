<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'client_name'        => 'required|string|max:120',
            'client_email'       => 'nullable|email|max:120',
            'client_phone'       => 'nullable|string|max:20',
            'delivery_mode'      => 'in:Standard,Express,Retrait',
            'delivery_address'   => 'nullable|string|max:500',
            'notes'              => 'nullable|string|max:1000',
            'total_amount'       => 'nullable|numeric|min:0',
            'items'              => 'nullable|array',
            'items.*.product_id' => 'required_with:items|exists:products,id',
            'items.*.quantity'   => 'required_with:items|integer|min:1|max:9999',
        ];
    }

    public function messages(): array
    {
        return [
            'client_name.required'       => 'Le nom du client est obligatoire.',
            'client_email.email'         => 'Adresse e-mail invalide.',
            'items.*.product_id.exists'  => 'Un produit sélectionné est introuvable.',
            'items.*.quantity.min'       => 'La quantité doit être au moins 1.',
        ];
    }
}
