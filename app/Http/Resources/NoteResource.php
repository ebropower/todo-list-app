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
//        return parent::toArray($request);
        return [
            'id' => $this->id,
//            'user_id' => $this->user_id,
            'details' => $this->details,
            'completed_at' => $this->completed_at,
            'owner' => new UserResource($this->user),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
