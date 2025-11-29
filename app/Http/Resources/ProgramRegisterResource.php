<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramRegisterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,

            'name' => $this->name,
            'start_date' => $this->start_date,
            'status' => $this->status,
            'form' => new FormWithSectionsResource($this->whenLoaded('form')),
            'program_detail' => new ProgramDetailResource($this->whenLoaded('programDetail')),
            'observations' => ObservationResource::collection($this->whenLoaded('observations')),
        ];
    }
}
