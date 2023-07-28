<?php

namespace App\Http\Resources;

use App\Entities\ArticleDefinition;
use App\Entities\BaseFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this[BaseFields::ID],
            'title' => $this[ArticleDefinition::TITLE],
            'headline' => $this[ArticleDefinition::HEADLINE],
            'leadParagraph' => $this[ArticleDefinition::LEAD_PARAGRAPH],
            'publishDate' => Carbon::parse($this[ArticleDefinition::PUBLISH_DATE])->getTimestamp(),
            'imageUrl' => $this[ArticleDefinition::IMAGE_URL],
            'articleUrl' => $this[ArticleDefinition::ARTICLE_URL],
            'category' => $this[ArticleDefinition::CATEGORY],
            'source' => $this->whenLoaded(ArticleDefinition::SOURCE_RELATION, new SourceResource($this[ArticleDefinition::SOURCE_RELATION])),
            'authors' => AuthorResource::collection($this->whenLoaded(ArticleDefinition::AUTHORS_RELATION))
        ];
    }
}
