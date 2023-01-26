<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'site' => $this->site,
            'score' => $this->score,
            'author' => $this->author,
            'created' => $this->created,
            'comments' => $this->comments,
        ];
    }
}
