<?php

namespace App\Http\Requests\Api\ProgramRegister;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgramRegisterWithDetailsRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'template' => 'required',
            'program_detail' => 'required|array',
            'program_detail.activity_id' => 'required|exists:activities,id',
            'program_detail.company_id' => 'required|exists:companies,id',
            'program_detail.department_id' => 'required|exists:departments,id',
            'program_detail.equipment_id' => 'required|exists:equipment,id',
            'program_detail.management_id' => 'required|exists:managements,id',
            'program_detail.observed_task_id' => 'required|exists:observed_tasks,id',
            'program_detail.position_id' => 'required|exists:positions,id',
            'program_detail.worksite_id' => 'required|exists:worksites,id',
        ];
    }
}
