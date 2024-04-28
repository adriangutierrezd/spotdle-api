<?php

namespace App\Http\Requests;

use App\Policies\TaskPolicy;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $task = $this->route('task');
        return $this->user() != null && $this->user()->can('update', [$task, TaskPolicy::class]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $method = $this->method();
        if($method === 'PUT'){
            return [
                'description' => ['sometimes', 'max:100'],
                'projectId' => [
                    'sometimes',
                    function ($attribute, $value, $fail) {
                        if (!is_null($value) && !\App\Models\Project::where('id', $value)->exists()) { 
                            $fail('El proyecto seleccionado no es válido.');
    
                        }
                    },
                ],                    'running' => ['required', 'boolean'],
                'started_at' => ['required', 'date'],
                'seconds' => ['required', 'integer']
            ];
        }else{
            return [
                'description' => ['sometimes', 'required', 'max:100'],
                'projectId' => ['sometimes', 'required', 'exists:projects,id'],
                'running' => ['sometimes', 'required', 'boolean'],
                'started_at' => ['sometimes', 'required', 'date'],
                'seconds' => ['sometimes', 'integer']
            ];
        }
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
