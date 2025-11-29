<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramDetailResource extends JsonResource
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

            'activity' => optional($this->activity)->only(['id', 'name']),
            'equipment' => optional($this->equipment)->only(['id', 'name']),
            'worksite' => optional($this->worksite)->only(['id', 'name']),
            'position' => optional($this->position)->only(['id', 'name']),
            'department' => optional($this->department)->only(['id', 'name']),
            'management' => optional($this->management)->only(['id', 'name']),
            'company' => optional($this->company)->only(['id', 'name']),
            'observed_task' => optional($this->observedTask)->only(['id', 'name']),
        ];
    }

}
