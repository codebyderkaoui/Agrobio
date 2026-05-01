<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'farm_id'        => 'required|exists:farms,id',
            'name'           => 'required|string|max:120',
            'description'    => 'nullable|string|max:1000',
            'category'       => 'required|string|max:60',
            'price'          => 'required|numeric|min:0.01|max:99999',
            'unit'           => 'required|string|max:30',
            'stock_status'   => 'in:ok,low,out',
            'stock_quantity' => 'integer|min:0',
            'emoji'          => 'nullable|string|max:10',
            'bg_class'       => 'nullable|string|max:10',
        ];
    }

    public function messages(): array
    {
        return [
            'farm_id.required'  => 'Veuillez sélectionner une ferme partenaire.',
            'farm_id.exists'    => 'La ferme sélectionnée est introuvable.',
            'name.required'     => 'Le nom du produit est obligatoire.',
            'name.max'          => 'Le nom ne peut pas dépasser 120 caractères.',
            'category.required' => 'La catégorie est obligatoire.',
            'price.required'    => 'Le prix est obligatoire.',
            'price.numeric'     => 'Le prix doit être un nombre.',
            'price.min'         => 'Le prix doit être supérieur à 0.',
            'unit.required'     => "L'unité de vente est obligatoire.",
        ];
    }
}
