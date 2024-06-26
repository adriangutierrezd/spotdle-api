<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() != null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'description' => ['sometimes', 'max:100'],
            'projectId' => [
                'sometimes',
                function ($attribute, $value, $fail) {
                    if (!is_null($value) && !\App\Models\Project::where('id', $value)->exists()) { 
                        $fail('El proyecto seleccionado no es válido.');

                    }
                },
            ],            'running' => ['required', 'boolean'],
            'started_at' => ['required', 'date'],
            'seconds' => ['sometimes', 'integer']
        ];
    }

    protected function prepareForValidation()
    {
        if($this->projectId){
            $this->merge([
                'project_id' => $this->projectId
            ]);
        }
    }
}

