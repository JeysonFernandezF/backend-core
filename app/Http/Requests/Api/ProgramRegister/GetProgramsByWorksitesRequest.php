<?php

namespace App\Http\Requests\Api\ProgramRegister;

use Illuminate\Foundation\Http\FormRequest;

class GetProgramsByWorksitesRequest extends FormRequest
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
            'worksite_ids' => 'required|array',
            'worksite_ids.*' => 'integer|exists:worksites,id'
        ];
    }
}
