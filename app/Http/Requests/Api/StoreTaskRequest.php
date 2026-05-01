<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string|max:2000',
            'category'    => 'required|string|max:60',
            'priority'    => 'in:high,med,low',
            'column'      => 'in:todo,inprog,review,done',
            'due_date'    => 'nullable|date|after_or_equal:today',
            'assigned_to' => 'nullable|exists:users,id',
            'project_id'  => 'nullable|exists:projects,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'         => 'Le titre de la tâche est obligatoire.',
            'title.max'              => 'Le titre ne peut pas dépasser 200 caractères.',
            'category.required'      => 'La catégorie est obligatoire.',
            'due_date.after_or_equal'=> "L'échéance ne peut pas être dans le passé.",
            'assigned_to.exists'     => "L'utilisateur assigné est introuvable.",
            'project_id.exists'      => 'Le projet sélectionné est introuvable.',
        ];
    }
}
