<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name'         => 'required|string|max:150',
            'description'  => 'nullable|string|max:2000',
            'status'       => 'in:Actif,Planifié,En pause,Terminé',
            'deadline'     => 'nullable|date',
            'member_ids'   => 'nullable|array',
            'member_ids.*' => 'exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'Le nom du projet est obligatoire.',
            'name.max'           => 'Le nom ne peut pas dépasser 150 caractères.',
            'member_ids.*.exists'=> 'Un membre sélectionné est introuvable.',
        ];
    }
}
