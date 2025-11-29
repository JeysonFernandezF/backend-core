<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecordQuestionsRequest extends FormRequest
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
            'program_register_id' => 'required|exists:program_registers,id',

            'data_observacion' => 'required|array',
            'data_observacion.people_observed' => 'required|integer|min:0',
            'data_observacion.observed_at' => 'required|date',
            'data_observacion.scheduled_at' => 'nullable|date',
            'data_observacion.date_in' => 'nullable|date',
            'data_observacion.date_end' => 'nullable|date',
            'data_observacion.comments' => 'nullable|string',
            'data_observacion.area_id' => 'nullable|exists:areas,id',
            'data_observacion.critical_risk_id' => 'nullable|exists:critical_risks,id',
            'data_observacion.turn_id' => 'nullable|exists:turns,id',

            'respuestas' => 'required|array|min:1',
            'respuestas.*.form_question_id' => 'required|exists:form_questions,id',
            'respuestas.*.conduct_id' => 'required|exists:conducts,id',
            'respuestas.*.barrier_id' => 'nullable|exists:barriers,id',
            'respuestas.*.notes' => 'nullable|string|max:500',
        ];
    }
}
