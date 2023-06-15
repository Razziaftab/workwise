<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleCollectionResource extends JsonResource
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
            'title' => $this->title,
            'authorName' => $this->author_name,
            'publishedDateTime' => $this->published_at->format('Y-m-d H:i:s'),
            'expiredDateTime' => $this->expired_at->format('Y-m-d H:i:s'),
        ];
    }
}
