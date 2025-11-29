<?php

namespace App\Http\Requests\Api\ProgramRegister;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgramRegisterRequest extends FormRequest
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
            'name' => 'required',
            'start_date' => 'required',
            'status' => 'required',
            'form_id' => ['nullable', 'exists:forms,id', 'unique:program_registers,form_id'],
            'program_detail_id' => ['required', 'exists:program_details,id']
        ];
    }
}
