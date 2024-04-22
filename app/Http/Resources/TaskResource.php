<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'userId' => $this->user_id,
            'projectId' => $this->project_id,
            'description' => $this->description,
            'date' => $this->date,
            'running' => $this->running,
            'seconds' => $this->seconds,
            'startedAt' => $this->started_at,
            'endedAt' => $this->ended_at,
            'project' => new ProjectResource($this->whenLoaded('project'))
        ];
    }
}
