<?php

namespace App\Http\Requests;

use App\Policies\ProjectPolicy;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $project = $this->route('project');
        return $this->user() != null && $this->user()->can('update', [$project, ProjectPolicy::class]);
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
                'name' => ['required', 'max:100'],
                'color' => ['required', 'max:7']
            ];
        }else{
            return [
                'name' => ['sometimes', 'required', 'max:100'],
                'color' => ['sometimes', 'required', 'max:7']
            ];
        }
    }
}
