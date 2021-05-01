<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Tweet extends JsonResource
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
            'content' => $this->content,
            'author' => new User($this->author),
            'created_at' => Carbon::parse($this->created_at)->format('F d, Y g:i A'),
            'updated_at' => Carbon::parse($this->updated_at)->format('F d, Y g:i A'),
        ];
    }
}
