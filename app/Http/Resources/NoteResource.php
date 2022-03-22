<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'details' => $this->details,
            'completed_at' => $this->completed_at,
            'owner' => new UserResource($this->user),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
