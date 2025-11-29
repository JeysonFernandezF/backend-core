<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ObservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'people_observed' => $this->people_observed,
            'observed_at' => $this->observed_at,
            'scheduled_at' => $this->scheduled_at,
            'date_in' => $this->date_in,
            'date_end' => $this->date_end,
            'comments' => $this->comments,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relaciones directas de Observation
            'user' => new UserResource($this->user),
            'area' => new AreaResource($this->area),
            'critical_risk' => new CriticalRiskResource($this->criticalRisk),
            'turn' => new TurnResource($this->turn),

            // Relación con program_register
            'program_register' => $this->whenLoaded('programRegister', function () {
                return [
                    'id' => $this->programRegister->id,
                    'name' => $this->programRegister->name,
                    'start_date' => $this->programRegister->start_date,
                    'status' => $this->programRegister->status,
                ];
            }),
        ];
    }
}
