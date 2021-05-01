<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Message extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'author' => new User($this->author),
            'content' => $this->content,
            'created_at' => $this->created_at->format('F d, Y g:i A'),
            'updated_at' => $this->updated_at->format('F d, Y g:i A'),
            'conversation_id' => $this->conversation_id,
        ];
    }
}
